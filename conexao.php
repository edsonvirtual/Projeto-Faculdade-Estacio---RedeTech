

<?php

$usuario = 'postgres';
$senha =  '1234';
$database = 'redtech';
$host = 'localhost';
$porta = '5432';

$dsn = "pgsql:host=$host;port=$porta;dbname=$database";

if($pgsql->error){
    die("Erro ao conectar ao banco de dados: " . $pgsql->error);
}