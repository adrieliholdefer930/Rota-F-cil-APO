<?php
require 'config/conexao.php';
require 'includes/funcoes.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $identificacao = trim($_POST['identificacao'] ?? '');
    $logradouro = trim($_POST['logradouro'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $uf = strtoupper(trim($_POST['uf'] ?? ''));

    if ($identificacao === '' || $logradouro === '' || $cidade === '' || strlen($uf) !== 2) {
        $erro = 'Preencha a identificação, o logradouro, a cidade e a UF com duas letras.';
    } elseif ($id > 0) {
        $comando = $conexao->prepare('UPDATE enderecos SET identificacao=?, logradouro=?, numero=?, bairro=?, cidade=?, uf=? WHERE id=?');
        $comando->bind_param('ssssssi', $identificacao, $logradouro, $numero, $bairro, $cidade, $uf, $id);
        $comando->execute();
        header('Location: enderecos.php?salvo=1');
        exit;
    } else {
        $comando = $conexao->prepare('INSERT INTO enderecos (identificacao, logradouro, numero, bairro, cidade, uf) VALUES (?, ?, ?, ?, ?, ?)');
        $comando->bind_param('ssssss', $identificacao, $logradouro, $numero, $bairro, $cidade, $uf);
        $comando->execute();
        header('Location: enderecos.php?salvo=1');
        exit;
    }
}

$editar = null;
if (isset($_GET['editar'])) {
    $id_editar = (int) $_GET['editar'];
    $consulta = $conexao->prepare('SELECT * FROM enderecos WHERE id=?');
    $consulta->bind_param('i', $id_editar);
    $consulta->execute();
    $editar = $consulta->get_result()->fetch_assoc();
}

$enderecos = $conexao->query('SELECT * FROM enderecos ORDER BY id DESC');
$pagina_ativa = 'enderecos';
$titulo_pagina = 'Endereços';
require 'includes/cabecalho.php';
?>
<section class="caixa caixa-formulario">
    <h2><?php echo $editar ? 'Editar endereço' : 'Cadastro de endereço'; ?></h2>
    <?php if ($erro): ?><div class="alert alert-danger mensagem"><?php echo texto($erro); ?></div><?php endif; ?>
    <?php if (isset($_GET['salvo'])): ?><div class="alert alert-success mensagem">Endereço salvo com sucesso.</div><?php endif; ?>
    <form method="post" action="enderecos.php">
        <input type="hidden" name="id" value="<?php echo (int) ($editar['id'] ?? 0); ?>">
        <div class="grade-endereco">
            <div class="campo metade">
                <label for="identificacao">Identificação do local</label>
                <input class="form-control" id="identificacao" name="identificacao" required value="<?php echo texto($editar['identificacao'] ?? ''); ?>">
            </div>
            <div class="campo metade">
                <label for="logradouro">Logradouro</label>
                <input class="form-control" id="logradouro" name="logradouro" required value="<?php echo texto($editar['logradouro'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="numero">Número</label>
                <input class="form-control" id="numero" name="numero" value="<?php echo texto($editar['numero'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="bairro">Bairro</label>
                <input class="form-control" id="bairro" name="bairro" value="<?php echo texto($editar['bairro'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="cidade">Cidade</label>
                <input class="form-control" id="cidade" name="cidade" required value="<?php echo texto($editar['cidade'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="uf">UF</label>
                <input class="form-control" id="uf" name="uf" maxlength="2" required value="<?php echo texto($editar['uf'] ?? ''); ?>">
            </div>
        </div>
        <div class="linha-botao">
            <button type="submit" class="btn btn-verde"><?php echo $editar ? 'Salvar alterações' : 'Salvar Endereço'; ?></button>
        </div>
    </form>
</section>
<section class="caixa caixa-listagem">
    <h2>Endereços cadastrados</h2>
    <div class="tabela-responsiva">
        <table class="tabela-cadastro">
            <thead><tr><th>Local</th><th>Logradouro</th><th>Bairro</th><th>Cidade / UF</th><th>Ação</th></tr></thead>
            <tbody>
                <?php while ($endereco = $enderecos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo texto($endereco['identificacao']); ?></td>
                    <td><?php echo texto($endereco['logradouro'] . ', ' . $endereco['numero']); ?></td>
                    <td><?php echo texto($endereco['bairro']); ?></td>
                    <td><?php echo texto($endereco['cidade'] . ' / ' . $endereco['uf']); ?></td>
                    <td><a class="btn-contorno" href="enderecos.php?editar=<?php echo (int) $endereco['id']; ?>">Editar</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require 'includes/rodape.php'; ?>
