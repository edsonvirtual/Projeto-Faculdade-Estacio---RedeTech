<?php
// Configurações de conexão
$host = '127.0.0.1'; // ou o IP do seu servidor PostgreSQL
$port = '5432';      // porta padrão do PostgreSQL
$dbname = 'ecommerce_db';
$user = 'postgres'; // nome de usuário do PostgreSQL
$password = '1234';

// String de conexão
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Criar conexão PDO
    $pdo = new PDO($dsn, $user, $password);
    
    // Configurar o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conexão com PostgreSQL estabelecida com sucesso!";
    
    // Exemplo de consulta
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetch();
    echo "<br>Versão do PostgreSQL: " . $version[0];
    
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
