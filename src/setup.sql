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
    nome VARCHAR(50),
    prezzo VARCHAR(50)
);

INSERT INTO utenti (username, password, ruolo) VALUES
('admin', 'admin123', 'amministratore'),
('user1', 'pass1', 'utente'),
('user2', 'pass2', 'utente');

INSERT INTO prodotti (nome, prezzo) VALUES
('iphone', '1000$'),
('macbook', '1200$'),
('airpods', '200$');