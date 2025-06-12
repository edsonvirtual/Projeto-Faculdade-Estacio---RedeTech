<?php
// Correção 1: Usar __DIR__ para caminho absoluto
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Ou correção 2: Se sua estrutura for diferente
// require_once __DIR__ . '/../includes/config.php'; // Se includes estiver um nível acima

// Restante do código...
session_start();

// Verificar se usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as cliente_nome 
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.cliente_id = ?
        ORDER BY p.data_pedido DESC
    ");
    $stmt->execute([$_SESSION['cliente_id']]);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar pedidos: " . $e->getMessage());
}

include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h2>Meus Pedidos</h2>
    
    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">Nenhum pedido encontrado.</div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nº Pedido</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td>#<?= $pedido['id'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                    <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                    <td>
                        <span class="badge 
                            <?= $pedido['status'] == 'completo' ? 'bg-success' : 
                               ($pedido['status'] == 'cancelado' ? 'bg-danger' : 'bg-warning') ?>">
                            <?= ucfirst($pedido['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-primary">
                            Detalhes
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>