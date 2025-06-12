<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'classes/PDFGenerator.php';


$stmt = $pdo->prepare("
    SELECT p.*, c.nome as cliente_nome, c.email, c.endereco, c.telefone 
    FROM pedidos p
    JOIN clientes c ON p.cliente_id = c.id
    WHERE p.id = ?
");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$pedido_id = $_GET['id'];

// Busca os dados do pedido no banco de dados
try {
    // Busca o pedido
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as cliente_nome, c.email, c.endereco, c.telefone 
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.id = ?
    ");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pedido) {
        die("Pedido não encontrado.");
    }
    
    // Busca os itens do pedido
    $stmt = $pdo->prepare("
        SELECT pi.*, pr.nome as produto_nome 
        FROM pedido_itens pi
        JOIN produtos pr ON pi.produto_id = pr.id
        WHERE pi.pedido_id = ?
    ");
    $stmt->execute([$pedido_id]);
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Prepara os dados para o PDF
    $cliente = [
        'nome' => $pedido['cliente_nome'],
        'email' => $pedido['email'],
        'endereco' => $pedido['endereco'],
        'telefone' => $pedido['telefone'],
        'metodo_pagamento' => $pedido['metodo_pagamento']
    ];
    
    $itens_formatados = [];
    foreach ($itens as $item) {
        $itens_formatados[] = [
            'nome' => $item['produto_nome'],
            'quantidade' => $item['quantidade'],
            'preco' => $item['preco_unitario'],
            'subtotal' => $item['preco_unitario'] * $item['quantidade']
        ];
    }
    
    // Gera o PDF
    $pdf = new PDFGenerator();
    $pdf_content = $pdf->generate($pedido_id, $cliente, $itens_formatados, $pedido['total']);
    
    // Envia o PDF para o navegador
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="pedido_' . $pedido_id . '.pdf"');
    echo $pdf_content;
    exit();
    
} catch (PDOException $e) {
    die("Erro ao buscar pedido: " . $e->getMessage());
}


?>