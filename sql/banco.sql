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

-- Dados ficticios apenas para facilitar a demonstracao da APO.
INSERT INTO produtos (nome, peso, descricao) VALUES
('Caixa de livros', 2.50, 'Livros para Transporte'),
('Monitor', 5.00, 'Monitor de computador');

INSERT INTO enderecos (identificacao, logradouro, numero, bairro, cidade, uf) VALUES
('Depósito Central', 'Avenida Iguaçu', '927', 'Centro', 'Nova Prata do Iguaçu', 'PR'),
('Cliente Oeste', 'Rua das Flores', '150', 'Centro', 'Dois Vizinhos', 'PR');

INSERT INTO transportadores (nome, documento, telefone, veiculo) VALUES
('Transportes 123', '99.999.999/0001-99', '(99) 99999-9999', 'Volvo FH 540');
