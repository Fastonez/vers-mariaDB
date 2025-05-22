DROP DATABASE IF EXISTS testdb;
CREATE DATABASE testdb;
USE testdb;

DROP TABLE IF EXISTS utenti;
CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ruolo VARCHAR(50) NOT NULL
);

DROP TABLE IF EXISTS prodotti;
CREATE TABLE prodotti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    prezzo DECIMAL(10,2) NOT NULL,
    quantita INT DEFAULT 1
);

INSERT INTO utenti (username, password, ruolo) VALUES
('admin', 'admin123', 'amministratore'),
('user1', 'pass1', 'utente'),
('user2', 'pass2', 'utente');

INSERT INTO prodotti (nome, prezzo) VALUES
('iphone', 1000.00),
('macbook', 2000.00),
('ipad', 800.00),
('apple watch', 400.00),
('airpods', 200.00);