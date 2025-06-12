<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Verifica se o ID do pedido foi fornecido
if (!isset($_GET['id'])) {
    header("Location: meus_pedidos.php");
    exit();
}

$pedido_id = $_GET['id'];

try {
    // Busca os dados do pedido
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
        SELECT pi.*, pr.nome as produto_nome, pr.descricao as produto_descricao
        FROM pedido_itens pi
        JOIN produtos pr ON pi.produto_id = pr.id
        WHERE pi.pedido_id = ?
    ");
    $stmt->execute([$pedido_id]);
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar pedido: " . $e->getMessage());
}

include 'templetes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Detalhes do Pedido #<?= $pedido_id ?></h3>
                </div>
                
                <div class="card-body">
                    <!-- Informações do Pedido -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informações do Pedido</h5>
                            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                            <p><strong>Status:</strong> 
                                <span class="badge 
                                    <?= $pedido['status'] == 'completo' ? 'bg-success' : 
                                       ($pedido['status'] == 'cancelado' ? 'bg-danger' : 'bg-warning') ?>">
                                    <?= ucfirst($pedido['status']) ?>
                                </span>
                            </p>
                            <p><strong>Método de Pagamento:</strong> <?= ucfirst($pedido['metodo_pagamento']) ?></p>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Informações do Cliente</h5>
                            <p><strong>Nome:</strong> <?= htmlspecialchars($pedido['cliente_nome']) ?></p>
                            <p><strong>E-mail:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
                            <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido['telefone']) ?></p>
                            <p><strong>Endereço:</strong> <?= nl2br(htmlspecialchars($pedido['endereco'])) ?></p>
                        </div>
                    </div>
                    
                    <!-- Itens do Pedido -->
                    <h5 class="mt-4">Itens do Pedido</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Descrição</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itens as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['produto_nome']) ?></td>
                                    <td><?= htmlspecialchars($item['produto_descricao']) ?></td>
                                    <td><?= $item['quantidade'] ?></td>
                                    <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                                    <td>R$ <?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Ações -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="meus_pedidos.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar para Meus Pedidos
                        </a>
                        <div>
                            <a href="pedido_pdf.php?id=<?= $pedido_id ?>" class="btn btn-primary" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
                            </a>
                            <?php if ($pedido['status'] == 'pendente'): ?>
                                <a href="cancelar_pedido.php?id=<?= $pedido_id ?>" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Cancelar Pedido
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templetes/footer.php'; ?>