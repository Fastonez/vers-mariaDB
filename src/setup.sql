CREATE DATABASE IF NOT EXISTS testdb;
USE testdb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    ruolo VARCHAR(50)
);

INSERT INTO users (username, password, ruolo) VALUES
('admin', 'admin123', 'amministratore'),
('user1', 'pass1', 'utente'),
('user2', 'pass2', 'utente');
