<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'classes/order.php';

if (!Auth::isLoggedIn() || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$order_id = $_GET['id'];
$order = Order::getById($order_id);
$items = Order::getItems($order_id);

// Verificar se o usuário tem permissão para ver este pedido
if (!$order || (!Auth::isAdmin() && $order->user_id != $_SESSION['user_id'])) {
    header('Location: orders.php');
    exit;
}

include 'templetes/header.php';
?>

<div class="container">
    <h2>Detalhes do Pedido #<?= $order_id ?></h2>
    
    <div class="row">
        <div class="col-md-6">
            <h4>Informações do Pedido</h4>
            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order->status) ?></p>
            <p><strong>Forma de Pagamento:</strong> <?= ucfirst($order->payment_method) ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($order->total_amount, 2, ',', '.') ?></p>
            <?php if ($order->notes): ?>
                <p><strong>Observações:</strong> <?= htmlspecialchars($order->notes) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="col-md-6">
            <h4>Cliente</h4>
            <?php 
                $db = Database::getConnection();
                $stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = ?");
                $stmt->execute([$order->customer_id]);
                $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><strong>Nome:</strong> <?= htmlspecialchars($customer['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($customer['phone']) ?></p>
            <p><strong>Endereço:</strong> <?= htmlspecialchars($customer['address']) ?></p>
        </div>
    </div>
    
    <h4 class="mt-4">Itens do Pedido</h4>
    <table class="table">
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
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['unit_price'] * $item['quantity'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td><strong>R$ <?= number_format($order->total_amount, 2, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="text-right">
        <a href="orders.php" class="btn btn-secondary">Voltar</a>
        <a href="order_pdf.php?id=<?= $order_id ?>" class="btn btn-primary" target="_blank">Gerar PDF</a>
    </div>
</div>

<?php include 'templetes/footer.php'; ?>