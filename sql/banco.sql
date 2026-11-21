-- Banco de dados do projeto Rota Facil, para importar pelo phpMyAdmin do XAMPP.
CREATE DATABASE IF NOT EXISTS rota_facil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rota_facil;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    descricao VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identificacao VARCHAR(150) NOT NULL,
    logradouro VARCHAR(150) NOT NULL,
    numero VARCHAR(20),
    bairro VARCHAR(100),
    cidade VARCHAR(100) NOT NULL,
    uf CHAR(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS transportadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    documento VARCHAR(25),
    telefone VARCHAR(30),
    veiculo VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS fretes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_frete DATE NOT NULL,
    transportador_id INT NOT NULL,
    origem_id INT NOT NULL,
    destino_id INT NOT NULL,
    FOREIGN KEY (transportador_id) REFERENCES transportadores(id),
    FOREIGN KEY (origem_id) REFERENCES enderecos(id),
    FOREIGN KEY (destino_id) REFERENCES enderecos(id)
);

CREATE TABLE IF NOT EXISTS frete_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frete_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (frete_id) REFERENCES fretes(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- As tabelas iniciam vazias. Os cadastros sao feitos pelo usuario no sistema.
