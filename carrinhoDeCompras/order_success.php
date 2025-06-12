<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'classes/Order.php';


if (!Auth::isLoggedIn() || !isset($_GET['order_id'])) {
    header('Location: login.php');
    exit;
}

$order_id = $_GET['order_id'];
$order = Order::getById($order_id);
$items = Order::getItems($order_id);

include 'templetes/header.php';
?>

<div class="container">
    <div class="alert alert-success">
        <h4 class="alert-heading">Pedido Realizado com Sucesso!</h4>
        <p>Obrigado por sua compra. Seu pedido foi registrado com o número #<?= $order_id ?>.</p>
        <hr>
        <p class="mb-0">Um email de confirmação foi enviado para o cliente.</p>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Detalhes do Pedido</h3>
            <p><strong>Número do Pedido:</strong> #<?= $order_id ?></p>
            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($order->total_amount, 2, ',', '.') ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order->status) ?></p>
            <p><strong>Forma de Pagamento:</strong> <?= ucfirst($order->payment_method) ?></p>
            
            <a href="order_pdf.php" class="btn btn-primary mt-3">Ver Todos os Pedidos</a>
            <a href="pedido.php" class="btn btn-secondary mt-3">Continuar Comprando</a>
        </div>
        
        <div class="col-md-6">
            <h3>Itens do Pedido</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
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
        </div>
    </div>
</div>

<?php include 'templetes/footer.php'; ?>