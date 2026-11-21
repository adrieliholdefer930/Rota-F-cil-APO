<?php
require 'config/conexao.php';

$data = $_GET['data'] ?? '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
    die('Escolha uma data valida na pagina de relatorios.');
}

// Busca todos os produtos incluidos nos fretes do dia selecionado.
$sql = 'SELECT f.data_frete,
        t.nome AS transportador,
        o.identificacao AS origem,
        d.identificacao AS destino,
        p.nome AS produto, fp.quantidade, p.peso
        FROM fretes f
        JOIN transportadores t ON t.id = f.transportador_id
        JOIN enderecos o ON o.id = f.origem_id
        JOIN enderecos d ON d.id = f.destino_id
        JOIN frete_produtos fp ON fp.frete_id = f.id
        JOIN produtos p ON p.id = fp.produto_id
        WHERE f.data_frete = ?
        ORDER BY f.id, fp.id';

$consulta = $conexao->prepare($sql);
$consulta->bind_param('s', $data);
$consulta->execute();
$dados = $consulta->get_result();

// CSV e um arquivo simples que pode ser aberto pelo Excel.
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="fretes_' . $data . '.csv"');

$arquivo = fopen('php://output', 'w');
fwrite($arquivo, "\xEF\xBB\xBF"); // Permite abrir os acentos no Excel.
fputcsv($arquivo, ['Data', 'Transportador', 'Origem', 'Destino', 'Produto', 'Quantidade', 'Peso unitario (kg)'], ';');

while ($linha = $dados->fetch_assoc()) {
    fputcsv($arquivo, [
        date('d/m/Y', strtotime($linha['data_frete'])),
        $linha['transportador'],
        $linha['origem'],
        $linha['destino'],
        $linha['produto'],
        $linha['quantidade'],
        number_format((float) $linha['peso'], 2, ',', '.')
    ], ';');
}
fclose($arquivo);
exit;
