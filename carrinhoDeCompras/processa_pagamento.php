<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'classes/Carrinho.php';
require_once 'classes/Email.php';
require_once 'classes/PDFGenerator.php';


// Verifica se há dados de checkout na sessão
if (!isset($_SESSION['checkout_data'])) {
    header("Location: checkout.php");
    exit();
}

$checkout_data = $_SESSION['checkout_data'];
$carrinho = new Carrinho($pdo);
$itens = $carrinho->getItens();
$total = $carrinho->getTotal();

// Processa o pedido no banco de dados
try {
    $pdo->beginTransaction();
    
    // 1. Insere o cliente (ou atualiza se já existir)
    $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, endereco, telefone) 
                          VALUES (?, ?, ?, ?) 
                          ON CONFLICT (email) DO UPDATE 
                          SET nome = EXCLUDED.nome, endereco = EXCLUDED.endereco, telefone = EXCLUDED.telefone
                          RETURNING id");
    $stmt->execute([
        $checkout_data['nome'],
        $checkout_data['email'],
        $checkout_data['endereco'],
        $checkout_data['telefone']
    ]);
    $cliente_id = $stmt->fetchColumn();
    
    // 2. Insere o pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, total, metodo_pagamento) 
                          VALUES (?, ?, ?) 
                          RETURNING id");
    $stmt->execute([
        $cliente_id,
        $total,
        $checkout_data['metodo_pagamento']
    ]);
    $pedido_id = $stmt->fetchColumn();
    
    // 3. Insere os itens do pedido
    foreach ($itens as $item) {
        $stmt = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $pedido_id,
            $item['id'],
            $item['quantidade'],
            $item['preco']
        ]);
        
        // Atualiza o estoque
        $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
        $stmt->execute([$item['quantidade'], $item['id']]);
    }
    
    $pdo->commit();
    
    // Gera o PDF do pedido
    $pdf = new PDFGenerator();
    $pdf_content = $pdf->generate($pedido_id, $checkout_data, $itens, $total);
    
    // Envia o email de confirmação
    $email = new Email();
    $email->send(
        $checkout_data['email'],
        "Confirmação de Pedido #$pedido_id",
        $this->getEmailTemplate($pedido_id, $checkout_data, $itens, $total),
        $pdf_content
    );
    
    // Limpa o carrinho
    $carrinho->limpar();
    unset($_SESSION['checkout_data']);
    
    // Redireciona para a página de agradecimento
    $_SESSION['pedido_id'] = $pedido_id;
    header("Location: obrigado.php");
    exit();
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro ao processar o pedido: " . $e->getMessage());
}
?>