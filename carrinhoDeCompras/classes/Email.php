<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Para usar o PHPMailer

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public static function sendOrderConfirmation($order_id, $customer_email, $customer_name)
    {
        // Configurações do servidor SMTP
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.seuprovedor.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'seuemail@dominio.com';
        $mail->Password = 'suasenha';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configurações do email
        $mail->setFrom('vendas@loja.com', 'Sua Loja');
        $mail->addAddress($customer_email, $customer_name);
        $mail->Subject = 'Confirmação de Pedido #' . $order_id;

        // Corpo do email em HTML
        $mail->isHTML(true);
        $mail->Body = self::getEmailTemplate($order_id);

        // Anexa o PDF
        $pdf_path = self::generatePDF($order_id);
        $mail->addAttachment($pdf_path, 'pedido_' . $order_id . '.pdf');

        if (!$mail->send()) {
            error_log('Erro ao enviar email: ' . $mail->ErrorInfo);
            return false;
        }

        return true;
    }

    private static function getEmailTemplate($order_id)
    {
        // Busca os dados do pedido no banco
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        // Busca os itens do pedido
        $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }

                .header {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                }

                .order-details {
                    margin: 20px 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th,
                td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                th {
                    background-color: #f2f2f2;
                }

                .total {
                    font-weight: bold;
                }
            </style>
        </head>

        <body>
            <div class="header">
                <h1>Pedido #<?= $order_id ?> Confirmado</h1>
                <p>Obrigado por comprar conosco!</p>
            </div>

            <div class="order-details">
                <h3>Detalhes do Pedido</h3>
                <p><strong>Data:</strong> <?= $order['created_at'] ?></p>
                <p><strong>Status:</strong> <?= $order['status'] ?></p>
                <p><strong>Método de Pagamento:</strong> <?= $order['metodo_pagamento'] ?></p>
            </div>

            <h3>Itens do Pedido</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($item['unit_price'] * $item['quantity'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total">
                        <td colspan="3">Total</td>
                        <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>

            <p>Atenciosamente,<br>Equipe da Loja</p>
        </body>

        </html>
        <?php
        return ob_get_clean();
    }

    public static function generatePDF($order_id)
    {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML(self::getEmailTemplate($order_id));

        $pdf_path = __DIR__ . '/../temp/pedido_' . $order_id . '.pdf';
        $mpdf->Output($pdf_path, 'F');

        return $pdf_path;
    }
}
?>