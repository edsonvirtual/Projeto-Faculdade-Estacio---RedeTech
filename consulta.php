<?php
require_once 'config.php'; // Inclui o arquivo de conexão
// Se config.php define uma classe ou namespace, importe assim:
// use Namespace\ClasseConfig;

try {
    $stmt = $pdo->query('SELECT * FROM usuario LIMIT 10');
    
    echo "<table border='1'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}