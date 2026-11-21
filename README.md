# Rota Fácil — APO de Fundamentos de Programação para Internet

Gerenciador de fretes feito para a APO da UNIPAR EAD, seguindo os cinco protótipos criados no Figma.

## O que o sistema faz

1. **Produtos**: cadastrar, listar e editar nome, peso e descrição.
2. **Endereços**: cadastrar, listar e editar identificação, logradouro, número, bairro, cidade e UF.
3. **Transportadores**: cadastrar, listar e editar nome, CPF/CNPJ, telefone e veículo.
4. **Fretes**: selecionar data, transportador, origem, destino e **adicionar vários produtos com quantidades**. O JavaScript acrescenta as linhas; o PHP salva os dados.
5. **Relatórios**: pesquisar os fretes de um dia e baixar o arquivo **CSV** correspondente.



## Tecnologias

- HTML: estrutura das telas.
- CSS e Bootstrap: aparência dos formulários, tabelas e botões.
- JavaScript: permite adicionar e editar os produtos do frete antes de salvar.
- PHP: recebe os formulários, grava os registros e prepara o relatório CSV.
- MySQL do XAMPP: armazena os dados para não perder os cadastros ao atualizar a página.

## Instalação no XAMPP (Windows)

1. Extraia a pasta `rota-facil` para `C:\xampp\htdocs\rota-facil`.
2. Abra o **Painel de Controle do XAMPP** e clique em **Start** para **Apache** e **MySQL**.
3. Abra o navegador em **http://localhost/phpmyadmin**.
4. Clique em **Importar** e escolha o arquivo `sql/banco.sql` desta pasta. Clique em **Executar**. O arquivo cria o banco `rota_facil`, as tabelas e alguns registros **fictícios** para teste.
5. Abra **http://localhost/rota-facil/** para acessar o sistema.

A conexão padrão fica em `config/conexao.php`: servidor `localhost`, usuário `root`, senha vazia e banco `rota_facil`. Esses são os valores usuais do XAMPP local. Se estiver usando senha própria, altere esse arquivo **apenas no computador** e não publique uma senha real no GitHub.

O Bootstrap e a fonte Poppins são carregados pela internet. Sem conexão, os estilos CSS próprios continuam disponíveis, com fonte alternativa.

## Como testar

1. Na página **Produtos**, confira o exemplo `Caixa de livros` ou cadastre outro produto. Teste **Editar**.
2. Na página **Endereços**, confira `Depósito Central` e `Cliente Oeste`. Também é possível cadastrar ou editar.
3. Na página **Transportadores**, confira o exemplo `Transportes 123` e teste o cadastro.
4. Na página **Fretes**, selecione a data, o transportador, a origem e o destino (devem ser diferentes).
5. Selecione `Caixa de livros`, informe a quantidade 2 e clique em **Adicionar Produto**. Repita escolhendo `Monitor` e quantidade 1. Os dois devem aparecer na tabela abaixo.
6. Clique em **Salvar Frete**. O sistema abrirá o relatório da data escolhida.
7. Na página **Relatórios**, confira o frete e clique em **Baixar relatório (CSV)**. Abra o arquivo no Excel ou em um editor de texto.
8. Para visualizar o registro de exemplo incluído no banco, escolha a data **15/11/2026** em Relatórios.



## Arquivos principais

```text
rota-facil/
├── index.php                 Abre a página Produtos
├── produtos.php              Cadastro e edição de produtos
├── enderecos.php             Cadastro e edição de endereços
├── transportadores.php       Cadastro e edição de transportadores
├── fretes.php                Cadastro do frete com vários produtos
├── relatorios.php            Pesquisa de fretes por data
├── baixar_relatorio.php      Download do CSV
├── assets/
│   ├── estilo.css            Cores e visual do Figma
│   └── fretes.js             Inclusão de produtos na tabela
├── config/
│   └── conexao.php           Conexão com o banco do XAMPP
├── includes/
│   ├── funcoes.php           Função simples de proteção do texto
│   ├── cabecalho.php         Menu horizontal
│   └── rodape.php            Final da página HTML
└── sql/
    └── banco.sql             Tabelas e dados fictícios
```


