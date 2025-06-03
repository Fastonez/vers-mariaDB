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

DROP TABLE IF EXISTS Magazzino;
CREATE TABLE Magazzino (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posizione VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS MagazzinoProdotti;
CREATE TABLE MagazzinoProdotti (
    magazzinoId INT,
    prodottoId INT,
    quantita INT NOT NULL DEFAULT 0,
    PRIMARY KEY (magazzinoId, prodottoId),
    FOREIGN KEY (magazzinoId) REFERENCES Magazzino(id) ON DELETE CASCADE,
    FOREIGN KEY (prodottoId) REFERENCES prodotti(id) ON DELETE CASCADE
);

INSERT INTO utenti (username, password, ruolo) VALUES
('admin', 'admin123', 'amministratore'),
('user1', 'pass1', 'utente'),
('user2', 'pass2', 'utente');

-- Inserimento di magazzini
INSERT INTO Magazzino (posizione) VALUES
('Roma'),
('Milano'),
('Napoli'),
('Torino'),
('Firenze');

-- Inserimento di prodotti (con nome, prezzo, quantità totale disponibile)
INSERT INTO prodotti (nome, prezzo, quantita) VALUES
('Laptop', 799.99, 19),
('Smartphone', 499.50, 23),
('Stampante', 159.90, 13),
('Monitor', 219.99, 12),
('Tastiera', 89.00, 30);

-- Inserimento di disponibilità dei prodotti nei magazzini
-- Tabella: disponibilita(magazzinoID, prodottoID, quantita)
INSERT INTO MagazzinoProdotti(magazzinoId, prodottoId, quantita) VALUES
(1, 1, 10), -- Roma - Laptop
(1, 2, 15), -- Roma - Smartphone
(2, 1, 5),  -- Milano - Laptop
(2, 3, 7),  -- Milano - Stampante
(3, 4, 12), -- Napoli - Monitor
(3, 5, 20), -- Napoli - Tastiera
(4, 2, 8),  -- Torino - Smartphone
(4, 3, 6),  -- Torino - Stampante
(5, 1, 4),  -- Firenze - Laptop
(5, 5, 10); -- Firenze - Tastiera
