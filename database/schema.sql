-- Schema para aplicação MVC PHP
-- Banco de dados: aula_php_mvc

CREATE DATABASE IF NOT EXISTS aula_php_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE aula_php_mvc;

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- Tabela de usuários (3ª entidade + autenticação)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'usuario') DEFAULT 'usuario',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir dados iniciais
INSERT INTO categorias (nome) VALUES 
('Eletrônicos'),
('Livros');

INSERT INTO produtos (nome, preco, categoria_id) VALUES 
('Notebook', 3500.00, 1),
('Harry Potter', 50.00, 2);

-- Inserir usuários de teste (senha: '123456' em bcrypt)
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@teste.com', '$2y$10$AmoVEo6KXEpSeluDH0YhBuJ.ZzB8A/BJb0Os9cPAnIjbMCqtfPkfe', 'admin'),
('Usuário Teste', 'usuario@teste.com', '$2y$10$AmoVEo6KXEpSeluDH0YhBuJ.ZzB8A/BJb0Os9cPAnIjbMCqtfPkfe', 'usuario');
