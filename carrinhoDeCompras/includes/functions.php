<?php
// Funções úteis

function redirect($url) {
    header("Location: " . BASE_URL . "/" . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['cliente_id']);
}

function getClienteNome() {
    return $_SESSION['cliente_nome'] ?? 'Visitante';
}

function formatPrice($price) {
    return 'R$ ' . number_format($price, 2, ',', '.');
}

function addToCart($produto_id, $quantidade = 1) {
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
    
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id] += $quantidade;
    } else {
        $_SESSION['carrinho'][$produto_id] = $quantidade;
    }
}

function removeFromCart($produto_id) {
    if (isset($_SESSION['carrinho'][$produto_id])) {
        unset($_SESSION['carrinho'][$produto_id]);
    }
}

function updateCartItem($produto_id, $quantidade) {
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id] = $quantidade;
    }
}

function getCartTotal() {
    global $pdo;
    $total = 0;
    
    if (!empty($_SESSION['carrinho'])) {
        $produtos_ids = array_keys($_SESSION['carrinho']);
        $placeholders = implode(',', array_fill(0, count($produtos_ids), '?'));
        
        $stmt = $pdo->prepare("SELECT id, preco FROM produtos WHERE id IN ($placeholders)");
        $stmt->execute($produtos_ids);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($produtos as $produto) {
            $quantidade = $_SESSION['carrinho'][$produto['id']];
            $total += $produto['preco'] * $quantidade;
        }
    }
    
    return $total;
}

function getCartItems() {
    global $pdo;
    $items = [];
    
    if (!empty($_SESSION['carrinho'])) {
        $produtos_ids = array_keys($_SESSION['carrinho']);
        $placeholders = implode(',', array_fill(0, count($produtos_ids), '?'));
        
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
        $stmt->execute($produtos_ids);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($produtos as $produto) {
            $items[] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'imagem' => $produto['imagem'],
                'quantidade' => $_SESSION['carrinho'][$produto['id']],
                'subtotal' => $produto['preco'] * $_SESSION['carrinho'][$produto['id']]
            ];
        }
    }
    
    return $items;
}

function clearCart() {
    unset($_SESSION['carrinho']);
}
?>