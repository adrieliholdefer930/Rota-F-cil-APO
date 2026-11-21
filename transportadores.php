<?php
require 'config/conexao.php';
require 'includes/funcoes.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $veiculo = trim($_POST['veiculo'] ?? '');

    if ($nome === '') {
        $erro = 'Preencha o nome do transportador.';
    } elseif ($id > 0) {
        $comando = $conexao->prepare('UPDATE transportadores SET nome=?, documento=?, telefone=?, veiculo=? WHERE id=?');
        $comando->bind_param('ssssi', $nome, $documento, $telefone, $veiculo, $id);
        $comando->execute();
        header('Location: transportadores.php?salvo=1');
        exit;
    } else {
        $comando = $conexao->prepare('INSERT INTO transportadores (nome, documento, telefone, veiculo) VALUES (?, ?, ?, ?)');
        $comando->bind_param('ssss', $nome, $documento, $telefone, $veiculo);
        $comando->execute();
        header('Location: transportadores.php?salvo=1');
        exit;
    }
}

$editar = null;
if (isset($_GET['editar'])) {
    $id_editar = (int) $_GET['editar'];
    $consulta = $conexao->prepare('SELECT * FROM transportadores WHERE id=?');
    $consulta->bind_param('i', $id_editar);
    $consulta->execute();
    $editar = $consulta->get_result()->fetch_assoc();
}

$transportadores = $conexao->query('SELECT * FROM transportadores ORDER BY id DESC');
$pagina_ativa = 'transportadores';
$titulo_pagina = 'Transportadores';
require 'includes/cabecalho.php';
?>
<section class="caixa caixa-formulario">
    <h2><?php echo $editar ? 'Editar transportador' : 'Cadastro de transportador'; ?></h2>
    <?php if ($erro): ?><div class="alert alert-danger mensagem"><?php echo texto($erro); ?></div><?php endif; ?>
    <?php if (isset($_GET['salvo'])): ?><div class="alert alert-success mensagem">Transportador salvo com sucesso.</div><?php endif; ?>
    <form method="post" action="transportadores.php">
        <input type="hidden" name="id" value="<?php echo (int) ($editar['id'] ?? 0); ?>">
        <div class="grade-transportador">
            <div class="campo">
                <label for="nome">Nome do Transportador</label>
                <input class="form-control" id="nome" name="nome" required value="<?php echo texto($editar['nome'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="documento">CPF / CNPJ</label>
                <input class="form-control" id="documento" name="documento" value="<?php echo texto($editar['documento'] ?? ''); ?>">
            </div>
            <div class="linha-dupla">
                <div class="campo">
                    <label for="telefone">Telefone</label>
                    <input class="form-control" id="telefone" name="telefone" value="<?php echo texto($editar['telefone'] ?? ''); ?>">
                </div>
                <div class="campo">
                    <label for="veiculo">Veículo</label>
                    <input class="form-control" id="veiculo" name="veiculo" value="<?php echo texto($editar['veiculo'] ?? ''); ?>">
                </div>
            </div>
        </div>
        <div class="linha-botao">
            <button type="submit" class="btn btn-verde"><?php echo $editar ? 'Salvar alterações' : 'Salvar Transportador'; ?></button>
        </div>
    </form>
</section>
<section class="caixa caixa-listagem">
    <h2>Transportadores cadastrados</h2>
    <div class="tabela-responsiva">
        <table class="tabela-cadastro">
            <thead><tr><th>Transportador</th><th>CPF / CNPJ</th><th>Telefone</th><th>Veículo</th><th>Ação</th></tr></thead>
            <tbody>
                <?php while ($transportador = $transportadores->fetch_assoc()): ?>
                <tr>
                    <td><?php echo texto($transportador['nome']); ?></td>
                    <td><?php echo texto($transportador['documento']); ?></td>
                    <td><?php echo texto($transportador['telefone']); ?></td>
                    <td><?php echo texto($transportador['veiculo']); ?></td>
                    <td><a class="btn-contorno" href="transportadores.php?editar=<?php echo (int) $transportador['id']; ?>">Editar</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require 'includes/rodape.php'; ?>
