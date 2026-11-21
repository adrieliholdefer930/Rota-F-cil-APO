<?php
require 'config/conexao.php';
require 'includes/funcoes.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $peso = (float) str_replace(',', '.', $_POST['peso'] ?? '0');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome === '' || $peso <= 0) {
        $erro = 'Preencha o nome e informe um peso maior que zero.';
    } elseif ($id > 0) {
        $comando = $conexao->prepare('UPDATE produtos SET nome=?, peso=?, descricao=? WHERE id=?');
        $comando->bind_param('sdsi', $nome, $peso, $descricao, $id);
        $comando->execute();
        header('Location: produtos.php?salvo=1');
        exit;
    } else {
        $comando = $conexao->prepare('INSERT INTO produtos (nome, peso, descricao) VALUES (?, ?, ?)');
        $comando->bind_param('sds', $nome, $peso, $descricao);
        $comando->execute();
        header('Location: produtos.php?salvo=1');
        exit;
    }
}

$editar = null;
if (isset($_GET['editar'])) {
    $id_editar = (int) $_GET['editar'];
    $consulta = $conexao->prepare('SELECT * FROM produtos WHERE id=?');
    $consulta->bind_param('i', $id_editar);
    $consulta->execute();
    $editar = $consulta->get_result()->fetch_assoc();
}

$produtos = $conexao->query('SELECT * FROM produtos ORDER BY id DESC');
$pagina_ativa = 'produtos';
$titulo_pagina = 'Produtos';
require 'includes/cabecalho.php';
?>
<section class="caixa caixa-formulario">
    <h2><?php echo $editar ? 'Editar produto' : 'Cadastro de produto'; ?></h2>
    <?php if ($erro): ?><div class="alert alert-danger mensagem"><?php echo texto($erro); ?></div><?php endif; ?>
    <?php if (isset($_GET['salvo'])): ?><div class="alert alert-success mensagem">Produto salvo com sucesso.</div><?php endif; ?>
    <form method="post" action="produtos.php">
        <input type="hidden" name="id" value="<?php echo (int) ($editar['id'] ?? 0); ?>">
        <div class="grade">
            <div class="campo">
                <label for="nome">Nome do produto</label>
                <input class="form-control" type="text" id="nome" name="nome" required value="<?php echo texto($editar['nome'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="peso">Peso (kg)</label>
                <input class="form-control" type="number" min="0.01" step="0.01" id="peso" name="peso" required value="<?php echo texto($editar['peso'] ?? ''); ?>">
            </div>
            <div class="campo campo-inteiro">
                <label for="descricao">Descrição</label>
                <input class="form-control" type="text" id="descricao" name="descricao" value="<?php echo texto($editar['descricao'] ?? ''); ?>">
            </div>
        </div>
        <div class="linha-botao">
            <button type="submit" class="btn btn-verde"><?php echo $editar ? 'Salvar alterações' : 'Salvar Produto'; ?></button>
        </div>
    </form>
</section>
<section class="caixa caixa-listagem">
    <h2>Produtos cadastrados</h2>
    <div class="tabela-responsiva">
        <table class="tabela-cadastro">
            <thead><tr><th>Produto</th><th>Peso (kg)</th><th>Descrição</th><th>Ação</th></tr></thead>
            <tbody>
                <?php while ($produto = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo texto($produto['nome']); ?></td>
                    <td><?php echo number_format((float) $produto['peso'], 2, ',', '.'); ?></td>
                    <td><?php echo texto($produto['descricao']); ?></td>
                    <td><a class="btn-contorno" href="produtos.php?editar=<?php echo (int) $produto['id']; ?>">Editar</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require 'includes/rodape.php'; ?>
