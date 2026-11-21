<?php
require 'config/conexao.php';
require 'includes/funcoes.php';

// O usuario escolhe o dia para consultar os fretes.
$data = $_GET['data'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
    $data = date('Y-m-d');
}

$sql = 'SELECT f.id, f.data_frete,
        t.nome AS transportador,
        o.identificacao AS origem,
        d.identificacao AS destino,
        COUNT(fp.id) AS produtos
        FROM fretes f
        JOIN transportadores t ON t.id = f.transportador_id
        JOIN enderecos o ON o.id = f.origem_id
        JOIN enderecos d ON d.id = f.destino_id
        JOIN frete_produtos fp ON fp.frete_id = f.id
        WHERE f.data_frete = ?
        GROUP BY f.id, f.data_frete, t.nome, o.identificacao, d.identificacao
        ORDER BY f.id DESC';

$consulta = $conexao->prepare($sql);
$consulta->bind_param('s', $data);
$consulta->execute();
$fretes = $consulta->get_result();

$pagina_ativa = 'relatorios';
$titulo_pagina = 'Relatórios';
require 'includes/cabecalho.php';
?>
<section class="caixa filtro-relatorio">
    <h2>Consultar fretes por data</h2>
    <?php if (isset($_GET['salvo'])): ?><div class="alert alert-success mensagem">Frete salvo com sucesso.</div><?php endif; ?>
    <form method="get" action="relatorios.php" class="filtro-linha">
        <div class="campo">
            <label for="data">Data do Frete</label>
            <input class="form-control" type="date" id="data" name="data" value="<?php echo texto($data); ?>" required>
        </div>
        <button class="btn btn-verde" type="submit">Pesquisar</button>
    </form>
</section>
<section class="caixa lista-relatorios">
    <h2>Fretes encontrados</h2>
    <div class="tabela-responsiva">
        <table class="tabela-cadastro">
            <thead>
                <tr><th>Data</th><th>Transportador</th><th>Origem</th><th>Destino</th><th>Produtos</th></tr>
            </thead>
            <tbody>
                <?php if ($fretes->num_rows === 0): ?>
                    <tr><td colspan="5">Nenhum frete encontrado nessa data.</td></tr>
                <?php else: ?>
                    <?php while ($frete = $fretes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($frete['data_frete'])); ?></td>
                        <td><?php echo texto($frete['transportador']); ?></td>
                        <td><?php echo texto($frete['origem']); ?></td>
                        <td><?php echo texto($frete['destino']); ?></td>
                        <td><?php echo (int) $frete['produtos']; ?> <?php echo (int) $frete['produtos'] === 1 ? 'produto' : 'produtos'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="rodape-relatorio">
        <a class="btn btn-verde" href="baixar_relatorio.php?data=<?php echo urlencode($data); ?>">Baixar relatório (CSV)</a>
    </div>
</section>
<?php require 'includes/rodape.php'; ?>
