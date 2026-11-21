// Inclui varios produtos no mesmo frete, sem recarregar a pagina.
const botaoAdicionar = document.getElementById('adicionar-produto');
const lista = document.getElementById('lista-produtos');
const seletorProduto = document.getElementById('produto_selecionado');
const campoQuantidade = document.getElementById('quantidade_selecionada');
const mensagemVazia = document.getElementById('nenhum-produto');

function mostrarMensagemVazia() {
    mensagemVazia.style.display = lista.children.length === 0 ? 'block' : 'none';
}

botaoAdicionar.addEventListener('click', function () {
    const opcao = seletorProduto.options[seletorProduto.selectedIndex];
    const produtoId = seletorProduto.value;
    const quantidade = Number(campoQuantidade.value);

    if (produtoId === '' || !Number.isInteger(quantidade) || quantidade <= 0) {
        alert('Selecione um produto e informe uma quantidade válida.');
        return;
    }

    const linha = document.createElement('tr');
    const nome = document.createElement('td');
    const peso = document.createElement('td');
    const quantidadeTexto = document.createElement('td');
    const acao = document.createElement('td');

    nome.textContent = opcao.textContent;
    peso.textContent = Number(opcao.dataset.peso).toFixed(2).replace('.', ',');
    quantidadeTexto.textContent = quantidade;

    // Campos ocultos enviam os IDs e as quantidades ao PHP.
    const campoProduto = document.createElement('input');
    campoProduto.type = 'hidden';
    campoProduto.name = 'produtos[]';
    campoProduto.value = produtoId;

    const campoQuantidadeOculto = document.createElement('input');
    campoQuantidadeOculto.type = 'hidden';
    campoQuantidadeOculto.name = 'quantidades[]';
    campoQuantidadeOculto.value = quantidade;

    const editar = document.createElement('button');
    editar.type = 'button';
    editar.className = 'btn-contorno';
    editar.textContent = 'Editar';
    editar.addEventListener('click', function () {
        // Leva os dados para o formulario e retira a linha para refazer.
        seletorProduto.value = produtoId;
        campoQuantidade.value = quantidade;
        linha.remove();
        mostrarMensagemVazia();
    });

    nome.appendChild(campoProduto);
    quantidadeTexto.appendChild(campoQuantidadeOculto);
    acao.appendChild(editar);
    linha.appendChild(nome);
    linha.appendChild(peso);
    linha.appendChild(quantidadeTexto);
    linha.appendChild(acao);
    lista.appendChild(linha);

    seletorProduto.value = '';
    campoQuantidade.value = '1';
    mostrarMensagemVazia();
});

document.getElementById('form-frete').addEventListener('submit', function (evento) {
    if (lista.children.length === 0) {
        evento.preventDefault();
        alert('Adicione ao menos um produto ao frete.');
        return;
    }
    if (document.getElementById('origem_id').value === document.getElementById('destino_id').value) {
        evento.preventDefault();
        alert('A origem e o destino não podem ser iguais.');
    }
});
