<?php
class Carrinho {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }
    
    public function adicionar($produto_id, $quantidade = 1) {
        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produto_id] = $quantidade;
        }
    }
    
    public function remover($produto_id) {
        if (isset($_SESSION['carrinho'][$produto_id])) {
            unset($_SESSION['carrinho'][$produto_id]);
        }
    }
    
    public function atualizar($produto_id, $quantidade) {
        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id] = $quantidade;
        }
    }
    
    public function limpar() {
        $_SESSION['carrinho'] = [];
    }
    
    public function getTotal() {
        $total = 0;
        
        if (!empty($_SESSION['carrinho'])) {
            $produtos_ids = array_keys($_SESSION['carrinho']);
            $placeholders = implode(',', array_fill(0, count($produtos_ids), '?'));
            
            $stmt = $this->pdo->prepare("SELECT id, preco FROM produtos WHERE id IN ($placeholders)");
            $stmt->execute($produtos_ids);
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($produtos as $produto) {
                $quantidade = $_SESSION['carrinho'][$produto['id']];
                $total += $produto['preco'] * $quantidade;
            }
        }
        
        return $total;
    }
    
    public function getItens() {
        $items = [];
        
        if (!empty($_SESSION['carrinho'])) {
            $produtos_ids = array_keys($_SESSION['carrinho']);
            $placeholders = implode(',', array_fill(0, count($produtos_ids), '?'));
            
            $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
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
    
    public function contarItens() {
        return array_sum($_SESSION['carrinho']);
    }
}
?>