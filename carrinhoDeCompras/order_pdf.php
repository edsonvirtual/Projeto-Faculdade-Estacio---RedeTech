<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'classes/Order.php';

if (!Auth::isLoggedIn() || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$order_id = $_GET['id'];
$order = Order::getById($order_id);

// Verificar permissões
if (!$order || (!Auth::isAdmin() && $order->user_id != $_SESSION['user_id'])) {
    header('Location: orders.php');
    exit;
}

// Gerar PDF
$pdf_path = Order::generatePDF($order_id);

// Enviar o PDF para o navegador
if (file_exists($pdf_path)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="pedido_' . $order_id . '.pdf"');
    header('Content-Length: ' . filesize($pdf_path));
    readfile($pdf_path);
    exit;
} else {
    die('Erro ao gerar o PDF.');
}
?>