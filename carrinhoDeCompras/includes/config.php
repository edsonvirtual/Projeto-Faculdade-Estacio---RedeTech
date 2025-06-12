<?php
// Configurações do banco de dados PostgreSQL
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'carrinhodecompras');
define('DB_USER', 'postgres');
define('DB_PASS', '1234');

// Configurações de email
define('EMAIL_FROM', 'edsonvirtual2@gmail.com');
define('EMAIL_NAME', 'Sistema de Vendas');

// Configurações do site
define('SITE_NAME', 'RedeTech');
define('BASE_URL', 'http://localhost/carrinhodecompras');

// Inicia a sessão
session_start();

// Inclui o autoloader de classes
spl_autoload_register(function ($class_name) {
    include __DIR__ . '/../classes/' . $class_name . '.php';
});
?>