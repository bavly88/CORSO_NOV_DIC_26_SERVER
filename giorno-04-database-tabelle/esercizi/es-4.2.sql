CREATE DATABASE lezione_sql CHARACTER SET utf8mb4 COLLATE UTF8MB4_UNICODE_CI;
-- 1. Tabella dei soci della biblioteca
USE lezione_sql;
CREATE TABLE soci (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- 2. Tabella dei libri
CREATE TABLE libri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(150) NOT NULL
);

-- 3. Tabella degli autori
CREATE TABLE autori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL
);

-- 4. Tabella ponte per la relazione N-N tra libri e autori
CREATE TABLE autori_libri (
    libro_id INT NOT NULL,
    autore_id INT NOT NULL,
    PRIMARY KEY (libro_id, autore_id),
    FOREIGN KEY (libro_id) REFERENCES libri(id) ON DELETE CASCADE,
    FOREIGN KEY (autore_id) REFERENCES autori(id) ON DELETE CASCADE
);

-- 5. Tabella dei prestiti effettuati
CREATE TABLE prestiti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    socio_id INT NOT NULL,
    libro_id INT NOT NULL,
    data_prestito DATE NOT NULL,
    FOREIGN KEY (socio_id) REFERENCES soci(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libri(id) ON DELETE CASCADE
);