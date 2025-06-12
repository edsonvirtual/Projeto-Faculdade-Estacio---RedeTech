<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';


if (!isset($_SESSION['pedido_id'])) {
    header("Location: index.php");
    exit();
}

$pedido_id = $_SESSION['pedido_id'];
unset($_SESSION['pedido_id']);

include 'includes/header.php';
?>

<div class="container text-center py-5">
    <h1 class="display-4">Obrigado pelo seu pedido!</h1>
    <p class="lead">Seu pedido foi recebido e está sendo processado.</p>
    <p>Número do pedido: <strong>#<?= $pedido_id ?></strong></p>
    <p>Enviamos um e-mail com os detalhes do seu pedido.</p>
    
    <div class="mt-4">
        <a href="produtos.php" class="btn btn-primary">Continuar Comprando</a>
        <a href="pedido_pdf.php?id=<?= $pedido_id ?>" class="btn btn-secondary" target="_blank">Imprimir Pedido</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>