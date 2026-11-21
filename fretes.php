<?php
require 'config/conexao.php';
require 'includes/funcoes.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data_frete'] ?? '';
    $transportador = (int) ($_POST['transportador_id'] ?? 0);
    $origem = (int) ($_POST['origem_id'] ?? 0);
    $destino = (int) ($_POST['destino_id'] ?? 0);
    $produtos = $_POST['produtos'] ?? [];
    $quantidades = $_POST['quantidades'] ?? [];

    // Os produtos foram adicionados no formulario pelo JavaScript.
    $itens = [];
    foreach ($produtos as $posicao => $produto) {
        $produto_id = (int) $produto;
        $quantidade = (int) ($quantidades[$posicao] ?? 0);
        if ($produto_id > 0 && $quantidade > 0) {
            $itens[] = ['produto' => $produto_id, 'quantidade' => $quantidade];
        }
    }

    if ($data === '' || $transportador <= 0 || $origem <= 0 || $destino <= 0) {
        $erro = 'Preencha a data, o transportador, a origem e o destino.';
    } elseif ($origem === $destino) {
        $erro = 'A origem e o destino precisam ser diferentes.';
    } elseif (count($itens) === 0) {
        $erro = 'Adicione pelo menos um produto antes de salvar o frete.';
    } else {
        // Salva o frete e, em seguida, cada produto ligado a ele.
        $conexao->begin_transaction();
        try {
            $comando = $conexao->prepare('INSERT INTO fretes (data_frete, transportador_id, origem_id, destino_id) VALUES (?, ?, ?, ?)');
            $comando->bind_param('siii', $data, $transportador, $origem, $destino);
            $comando->execute();
            $frete_id = $conexao->insert_id;

            $comando_item = $conexao->prepare('INSERT INTO frete_produtos (frete_id, produto_id, quantidade) VALUES (?, ?, ?)');
            foreach ($itens as $item) {
                $produto_id = $item['produto'];
                $quantidade = $item['quantidade'];
                $comando_item->bind_param('iii', $frete_id, $produto_id, $quantidade);
                $comando_item->execute();
            }
            $conexao->commit();
            header('Location: relatorios.php?data=' . urlencode($data) . '&salvo=1');
            exit;
        } catch (Exception $erro_banco) {
            $conexao->rollback();
            $erro = 'Não foi possível salvar o frete. Confira os dados selecionados.';
        }
    }
}

$transportadores = $conexao->query('SELECT id, nome FROM transportadores ORDER BY nome');
$enderecos = $conexao->query('SELECT id, identificacao, cidade FROM enderecos ORDER BY identificacao')->fetch_all(MYSQLI_ASSOC);
$produtos_disponiveis = $conexao->query('SELECT id, nome, peso FROM produtos ORDER BY nome');

$pagina_ativa = 'fretes';
$titulo_pagina = 'Fretes';
require 'includes/cabecalho.php';
?>
<form id="form-frete" method="post" action="fretes.php">
<section class="caixa">
    <h2>Cadastro de Fretes</h2>
    <?php if ($erro): ?><div class="alert alert-danger mensagem"><?php echo texto($erro); ?></div><?php endif; ?>
        <div class="grade">
            <div class="campo">
                <label for="data_frete">Data do Frete</label>
                <input type="date" class="form-control" id="data_frete" name="data_frete" required value="<?php echo texto($_POST['data_frete'] ?? date('Y-m-d')); ?>">
            </div>
            <div class="campo">
                <label for="transportador_id">Transportador</label>
                <select class="form-select" id="transportador_id" name="transportador_id" required>
                    <option value="">Selecione o transportador</option>
                    <?php while ($t = $transportadores->fetch_assoc()): ?>
                    <option value="<?php echo (int) $t['id']; ?>"><?php echo texto($t['nome']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="origem_id">Origem</label>
                <select class="form-select" id="origem_id" name="origem_id" required>
                    <option value="">Selecione a origem</option>
                    <?php foreach ($enderecos as $e): ?>
                    <option value="<?php echo (int) $e['id']; ?>"><?php echo texto($e['identificacao'] . ' - ' . $e['cidade']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="campo">
                <label for="destino_id">Destino</label>
                <select class="form-select" id="destino_id" name="destino_id" required>
                    <option value="">Selecione o destino</option>
                    <?php foreach ($enderecos as $e): ?>
                    <option value="<?php echo (int) $e['id']; ?>"><?php echo texto($e['identificacao'] . ' - ' . $e['cidade']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="campo">
                <label for="produto_selecionado">Produto</label>
                <select class="form-select" id="produto_selecionado">
                    <option value="">Selecione um produto</option>
                    <?php while ($p = $produtos_disponiveis->fetch_assoc()): ?>
                    <option value="<?php echo (int) $p['id']; ?>" data-peso="<?php echo texto($p['peso']); ?>"><?php echo texto($p['nome']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="quantidade_selecionada">Quantidade</label>
                <div class="d-flex gap-3">
                    <input type="number" class="form-control" id="quantidade_selecionada" min="1" step="1" value="1">
                    <button type="button" id="adicionar-produto" class="btn btn-verde flex-shrink-0">Adicionar Produto</button>
                </div>
            </div>
        </div>
</section>
<section class="caixa caixa-listagem">
        <div class="caixa-itens">
            <div class="tabela-responsiva">
                <table class="tabela-fretes">
                    <thead><tr><th>Produto</th><th>Peso (kg)</th><th>Quantidade</th><th>Ação</th></tr></thead>
                    <tbody id="lista-produtos">
                        <!-- O JavaScript inclui os produtos selecionados aqui. -->
                    </tbody>
                </table>
            </div>
            <p id="nenhum-produto" class="texto-vazio">Nenhum produto adicionado.</p>
            <div class="linha-botao">
                <button type="submit" class="btn btn-verde">Salvar Frete</button>
            </div>
        </div>
</section>
</form>
<script src="assets/fretes.js"></script>
<?php require 'includes/rodape.php'; ?>
