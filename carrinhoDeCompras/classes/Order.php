<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

class Order {
    public $order_id;
    public $customer_id;
    public $user_id;
    public $total_amount;
    public $status;
    public $payment_method;
    public $notes;
    public $created_at;
    
    public function save() {
        $db = Database::getConnection();
        
        if ($this->order_id) {
            // Atualizar pedido existente
            $stmt = $db->prepare("UPDATE orders SET customer_id = ?, user_id = ?, total_amount = ?, status = ?, payment_method = ?, notes = ?, updated_at = NOW() WHERE order_id = ?");
            $stmt->execute([
                $this->customer_id,
                $this->user_id,
                $this->total_amount,
                $this->status,
                $this->payment_method,
                $this->notes,
                $this->order_id
            ]);
        } else {
            // Criar novo pedido
            $stmt = $db->prepare("INSERT INTO orders (customer_id, user_id, total_amount, status, payment_method, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $this->customer_id,
                $this->user_id,
                $this->total_amount,
                $this->status,
                $this->payment_method,
                $this->notes
            ]);
            
            $this->order_id = $db->lastInsertId();
        }
        
        return $this->order_id;
    }
    
    public static function getById($order_id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
            $obj = new self();
            foreach ($order as $key => $value) {
                $obj->$key = $value;
            }
            return $obj;
        }
        return null;
    }
    
    public static function getItems($order_id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT oi.*, p.name as product_name 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.product_id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function sendEmailConfirmation($order_id) {
        $order = self::getById($order_id);
        if (!$order) return false;
        
        $db = Database::getConnection();
        $customer_stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = ?");
        $customer_stmt->execute([$order->customer_id]);
        $customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$customer || !filter_var($customer['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $items = self::getItems($order_id);
        
        // Construir o corpo do email
        $subject = "Confirmação de Pedido #" . $order_id;
        
        $message = "<h1>Pedido #$order_id</h1>";
        $message .= "<p>Data: " . date('d/m/Y H:i', strtotime($order->created_at)) . "</p>";
        $message .= "<p>Cliente: " . htmlspecialchars($customer['name']) . "</p>";
        $message .= "<p>Email: " . htmlspecialchars($customer['email']) . "</p>";
        $message .= "<p>Status: " . htmlspecialchars($order->status) . "</p>";
        $message .= "<p>Forma de Pagamento: " . htmlspecialchars($order->payment_method) . "</p>";
        
        $message .= "<h3>Itens do Pedido</h3>";
        $message .= "<table border='1' cellpadding='5' style='width:100%; border-collapse: collapse;'>";
        $message .= "<tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Subtotal</th></tr>";
        
        foreach ($items as $item) {
            $message .= "<tr>";
            $message .= "<td>" . htmlspecialchars($item['product_name']) . "</td>";
            $message .= "<td style='text-align: center;'>" . $item['quantity'] . "</td>";
            $message .= "<td style='text-align: right;'>R$ " . number_format($item['unit_price'], 2, ',', '.') . "</td>";
            $message .= "<td style='text-align: right;'>R$ " . number_format($item['unit_price'] * $item['quantity'], 2, ',', '.') . "</td>";
            $message .= "</tr>";
        }
        
        $message .= "<tr><td colspan='3' style='text-align: right;'><strong>Total</strong></td><td style='text-align: right;'><strong>R$ " . number_format($order->total_amount, 2, ',', '.') . "</strong></td></tr>";
        $message .= "</table>";
        
        // Configurar headers para email HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . EMAIL_NAME . " <" . EMAIL_FROM . ">\r\n";
        $headers .= "Reply-To: " . EMAIL_FROM . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Enviar email
        return mail($customer['email'], $subject, $message, $headers);
    }
    
    public static function generatePDF($order_id) {
        // Certifique-se de que o arquivo TCPDF está no caminho correto
        require_once __DIR__ . '/../libs/tcpdf/tcpdf.php';
        if (!class_exists('TCPDF')) {
            throw new Exception('TCPDF library not found or not loaded.');
        }
        
        $order = self::getById($order_id);
        if (!$order) return false;
        
        $db = Database::getConnection();
        $customer_stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = ?");
        $customer_stmt->execute([$order->customer_id]);
        $customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$customer) return false;
        
        $items = self::getItems($order_id);
        
        // Criar novo documento PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Configurar documento
        $pdf->SetCreator('TCPDF');
        $pdf->SetAuthor(SITE_NAME);
        $pdf->SetTitle('Pedido #' . $order_id);
        $pdf->SetSubject('Pedido #' . $order_id);
        
        // Adicionar página
        $pdf->AddPage();
        
        // Conteúdo do PDF
        $html = '<h1>Pedido #' . $order_id . '</h1>';
        $html .= '<p><strong>Data:</strong> ' . date('d/m/Y H:i', strtotime($order->created_at)) . '</p>';
        $html .= '<p><strong>Cliente:</strong> ' . htmlspecialchars($customer['name']) . '</p>';
        $html .= '<p><strong>Email:</strong> ' . htmlspecialchars($customer['email']) . '</p>';
        $html .= '<p><strong>Status:</strong> ' . htmlspecialchars($order->status) . '</p>';
        $html .= '<p><strong>Forma de Pagamento:</strong> ' . htmlspecialchars($order->payment_method) . '</p>';
        
        $html .= '<h3>Itens do Pedido</h3>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr style="background-color:#f2f2f2;"><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Subtotal</th></tr>';
        
        foreach ($items as $item) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($item['product_name']) . '</td>';
            $html .= '<td style="text-align: center;">' . $item['quantity'] . '</td>';
            $html .= '<td style="text-align: right;">R$ ' . number_format($item['unit_price'], 2, ',', '.') . '</td>';
            $html .= '<td style="text-align: right;">R$ ' . number_format($item['unit_price'] * $item['quantity'], 2, ',', '.') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '<tr style="background-color:#f2f2f2;"><td colspan="3" style="text-align: right;"><strong>Total</strong></td><td style="text-align: right;"><strong>R$ ' . number_format($order->total_amount, 2, ',', '.') . '</strong></td></tr>';
        $html .= '</table>';
        
        // Escrever conteúdo HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Criar diretório se não existir
        $pdf_dir = __DIR__ . '/../pdfs';
        if (!file_exists($pdf_dir)) {
            mkdir($pdf_dir, 0777, true);
        }
        
        // Salvar arquivo PDF
        $pdf_path = $pdf_dir . '/pedido_' . $order_id . '.pdf';
        $pdf->Output($pdf_path, 'F');
        
        return $pdf_path;
    }
}
?>