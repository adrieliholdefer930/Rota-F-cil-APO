<?php
// Conexao padrao do XAMPP: usuario root e senha vazia.
$conexao = new mysqli('localhost', 'root', '', 'rota_facil');

if ($conexao->connect_error) {
    die('Erro ao conectar ao banco. Verifique o MySQL e importe sql/banco.sql.');
}

$conexao->set_charset('utf8mb4');
?>
