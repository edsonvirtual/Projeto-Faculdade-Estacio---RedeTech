<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/classes/Cart.php';
require_once __DIR__ . '/classes/Order.php';

// Verificar autenticação
if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Verificar se o carrinho está vazio
if (empty($_SESSION['cart'])) {
    header('Location: products.php');
    exit;
}

// Inicializar variáveis
$error = null;
$customers = [];

try {
    // Buscar clientes
    $db = Database::getConnection();
    $stmt = $db->query("SELECT * FROM customers ORDER BY name");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Erro ao carregar clientes: " . $e->getMessage();
}

// Processar finalização da compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;
    $notes = $_POST['notes'] ?? '';
    
    // Validação básica
    if (empty($customer_id)) {
        $error = "Selecione um cliente";
    } elseif (empty($payment_method)) {
        $error = "Selecione um método de pagamento";
    } else {
        try {
            $db = Database::getConnection();
            $db->beginTransaction();
            
            // Criar o pedido
            $order = new Order();
            $order->customer_id = $customer_id;
            $order->user_id = $_SESSION['user_id'];
            $order->total_amount = Cart::getTotal();
            $order->status = 'pending'; // Alterado para 'pending' até confirmação de pagamento
            $order->payment_method = $payment_method;
            $order->notes = $notes;
            
            $order_id = $order->save();
            
            // Adicionar itens do pedido
            foreach (Cart::getItems() as $item) {
                // Verificar estoque antes de processar
                $stmt = $db->prepare("SELECT stock FROM products WHERE product_id = ?");
                $stmt->execute([$item['product']['product_id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$product || $product['stock'] < $item['quantity']) {
                    throw new Exception("Estoque insuficiente para o produto: " . $item['product']['name']);
                }
                
                // Inserir item do pedido
                $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $order_id,
                    $item['product']['product_id'],
                    $item['quantity'],
                    $item['product']['price']
                ]);
                
                // Atualizar estoque
                $stmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
                $stmt->execute([$item['quantity'], $item['product']['product_id']]);
            }
            
            $db->commit();
            
            // Limpar carrinho
            //Cart::clear();
            
            // Redirecionar para página de sucesso
            header("Location: order_success.php?order_id=$order_id");
            exit;
            
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $error = "Erro ao processar o pedido: " . $e->getMessage();
        }
    }
}

include __DIR__ . '/templetes/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Finalizar Compra</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Resumo do Pedido</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (Cart::getItems() as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product']['name']) ?></td>
                                    <td>R$ <?= number_format($item['product']['price'], 2, ',', '.') ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="3" class="text-right font-weight-bold">Total:</td>
                                <td class="font-weight-bold">R$ <?= number_format(Cart::getTotal(), 2, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Informações da Compra</h4>
                </div>
                <div class="card-body">
                    <form method="post" id="checkoutForm">
                        <div class="form-group">
                            <label for="customer_id">Cliente *</label>
                            <select class="form-control" id="customer_id" name="customer_id" required>
                                <option value="">Selecione um cliente</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['customer_id'] ?>" 
                                        <?= isset($_POST['customer_id']) && $_POST['customer_id'] == $customer['customer_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($customer['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">
                                <a href="customers.php?action=add">Cadastrar novo cliente</a>
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method">Forma de Pagamento *</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Selecione...</option>
                                <option value="cash" <?= isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash' ? 'selected' : '' ?>>Dinheiro</option>
                                <option value="credit" <?= isset($_POST['payment_method']) && $_POST['payment_method'] == 'credit' ? 'selected' : '' ?>>Cartão de Crédito</option>
                                <option value="debit" <?= isset($_POST['payment_method']) && $_POST['payment_method'] == 'debit' ? 'selected' : '' ?>>Cartão de Débito</option>
                                <option value="transfer" <?= isset($_POST['payment_method']) && $_POST['payment_method'] == 'transfer' ? 'selected' : '' ?>>Transferência Bancária</option>
                                <option value="pix" <?= isset($_POST['payment_method']) && $_POST['payment_method'] == 'pix' ? 'selected' : '' ?>>PIX</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Observações</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '' ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-check-circle"></i> Confirmar Pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/templetes/footer.php'; ?>