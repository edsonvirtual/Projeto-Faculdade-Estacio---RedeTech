<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Remover item do carrinho
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}

// Atualizar quantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: cart.php');
    exit;
}

// Buscar produtos no carrinho
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $db = Database::getConnection();
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    
    $stmt = $db->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $product) {
        $quantity = $_SESSION['cart'][$product['product_id']];
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;
        
        $cart_items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

include 'templetes/header.php';
?>

<div class="container">
    <h2>Carrinho de Compras</h2>
    
    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Seu carrinho está vazio.</div>
        <a href="products.php" class="btn btn-primary">Continuar Comprando</a>
    <?php else: ?>
        <form method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product']['name']) ?></td>
                            <td>R$ <?= number_format($item['product']['price'], 2, ',', '.') ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $item['product']['product_id'] ?>]" 
                                       value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 80px;">
                            </td>
                            <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                            <td>
                                <a href="cart.php?remove=<?= $item['product']['product_id'] ?>" class="btn btn-danger btn-sm">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
                <a href="products.php" class="btn btn-secondary mt-3" >Continuar Comprando</a>
            </table>
          
            <div class="text-right">
                
                <button type="submit" name="update_cart" class="btn btn-secondary">Atualizar Carrinho</button>
                <a href="checkout.php" class="btn btn-primary">Finalizar Compra</a>
                
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'templetes/footer.php'; ?>