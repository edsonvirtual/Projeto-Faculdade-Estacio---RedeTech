<?php
require_once __DIR__ . '/../includes/db.php';

class Cart {
    public static function getItems() {
        if (empty($_SESSION['cart'])) {
            return [];
        }
        
        $db = Database::getConnection();
        $product_ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        
        $stmt = $db->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
        $stmt->execute($product_ids);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $items = [];
        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['product_id']];
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
        }
        
        return $items;
    }
    
    public static function getTotal() {
        $total = 0;
        foreach (self::getItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
    
    public static function countItems() {
        if (empty($_SESSION['cart'])) {
            return 0;
        }
        return array_sum($_SESSION['cart']);
    }
}
?>