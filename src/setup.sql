CREATE DATABASE IF NOT EXISTS testdb;
USE testdb;

CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    ruolo VARCHAR(50)
);

CREATE TABLE prodotti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    prezzo INT NOT NULL,
    quantita INT DEFAULT 1
);

INSERT INTO utenti (username, password, ruolo) VALUES
('admin', 'admin123', 'amministratore'),
('user1', 'pass1', 'utente'),
('user2', 'pass2', 'utente');

INSERT INTO prodotti (nome, prezzo) VALUES
('iphone', 1000),
('macbook', 2000),
('ipad', 800),
('apple watch', 400),
('airpods', 200);
