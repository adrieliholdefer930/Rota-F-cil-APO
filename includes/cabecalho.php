<?php
// $pagina_ativa e $titulo_pagina sao definidos em cada pagina.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo texto($titulo_pagina); ?> - Rota Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/estilo.css">
</head>
<body>
<header class="barra-superior">
    <a class="marca" href="produtos.php">ROTA FÁCIL</a>
    <nav class="menu" aria-label="Menu principal">
        <a class="<?php echo $pagina_ativa === 'produtos' ? 'ativo' : ''; ?>" href="produtos.php">Produtos</a>
        <a class="<?php echo $pagina_ativa === 'enderecos' ? 'ativo' : ''; ?>" href="enderecos.php">Endereços</a>
        <a class="<?php echo $pagina_ativa === 'transportadores' ? 'ativo' : ''; ?>" href="transportadores.php">Transportadores</a>
        <a class="<?php echo $pagina_ativa === 'fretes' ? 'ativo' : ''; ?>" href="fretes.php">Fretes</a>
        <a class="<?php echo $pagina_ativa === 'relatorios' ? 'ativo' : ''; ?>" href="relatorios.php">Relatórios</a>
    </nav>
</header>
<main class="conteudo">
    <h1><?php echo texto($titulo_pagina); ?></h1>
