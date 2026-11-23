-- =====================================================================
-- 00 - La tabella degli utenti, aggiunta al database biblioteca
-- Eseguire in HeidiSQL prima della lezione.
-- =====================================================================
USE biblioteca;

DROP TABLE IF EXISTS utenti;
CREATE TABLE utenti (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    password_hash  VARCHAR(255) NOT NULL   -- MAI la password in chiaro: solo il suo hash
);

-- La tabella dei commenti, per la dimostrazione XSS
DROP TABLE IF EXISTS commenti;
CREATE TABLE commenti (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    testo  TEXT NOT NULL
);

-- La colonna per il nome del file della copertina (upload)
ALTER TABLE libri ADD COLUMN copertina VARCHAR(100) NULL;
