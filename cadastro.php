<?php
require 'conexao.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->execute([$nome, $email, $senha]);

header("Location: login.html");
exit;
?>
