<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Adicionar produto ao carrinho
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    header('Location: cart.php');
    exit;
}

// Buscar produtos
$db = Database::getConnection();
$stmt = $db->query("SELECT * FROM products ORDER BY name");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'templetes/header.php';
?>

<div class="container">
    <h2>Produtos</h2>
    
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <?php if ($product['image_url']): ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="card-text"><strong>R$ <?= number_format($product['price'], 2, ',', '.') ?></strong></p>
                        
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <div class="form-group">
                                <label for="quantity-<?= $product['product_id'] ?>">Quantidade:</label>
                                <input type="number" id="quantity-<?= $product['product_id'] ?>" name="quantity" value="1" min="1" class="form-control" style="width: 80px;">
                            </div>
                            <button type="submit" name="add_to_cart" class="btn btn-primary">Adicionar ao Carrinho</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'templetes/footer.php'; ?>