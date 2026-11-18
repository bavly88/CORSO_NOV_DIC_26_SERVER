-- =====================================================================
-- Database "biblioteca" - schema normalizzato (3NF)
-- MariaDB 11.8 (il database del corso); compatibile con MySQL 8.0.13+
-- Esecuzione: HeidiSQL (File > Load SQL file, poi F9) oppure
--             mariadb -u corso -p < 00-schema-biblioteca.sql
-- =====================================================================

DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

-- ---------------------------------------------------------------------
-- Tabelle "anagrafiche": non dipendono da nessun'altra
-- ---------------------------------------------------------------------

CREATE TABLE editori (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome   VARCHAR(100) NOT NULL UNIQUE,
    citta  VARCHAR(80)  NULL
);

CREATE TABLE generi (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome  VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE autori (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(60)  NOT NULL,
    cognome       VARCHAR(60)  NOT NULL,
    nazionalita   VARCHAR(40)  NULL,
    anno_nascita  SMALLINT     NULL
);

CREATE TABLE soci (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome             VARCHAR(60)  NOT NULL,
    cognome          VARCHAR(60)  NOT NULL,
    email            VARCHAR(150) NOT NULL UNIQUE,
    data_iscrizione  DATE         NOT NULL DEFAULT (CURRENT_DATE)
);

-- ---------------------------------------------------------------------
-- libri: relazione 1-N con editori e generi
-- (un editore pubblica molti libri, un libro ha un solo editore)
-- ---------------------------------------------------------------------

CREATE TABLE libri (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titolo      VARCHAR(200) NOT NULL,
    isbn        CHAR(13)     NULL UNIQUE,
    anno        SMALLINT     NULL,
    pagine      SMALLINT UNSIGNED NULL,
    editore_id  INT UNSIGNED NULL,
    genere_id   INT UNSIGNED NULL,

    CONSTRAINT fk_libri_editore FOREIGN KEY (editore_id)
        REFERENCES editori(id)
        ON DELETE SET NULL      -- se elimino l'editore, il libro resta senza editore
        ON UPDATE CASCADE,

    CONSTRAINT fk_libri_genere FOREIGN KEY (genere_id)
        REFERENCES generi(id)
        ON DELETE RESTRICT      -- non posso eliminare un genere usato da qualche libro
        ON UPDATE CASCADE,

    CONSTRAINT chk_pagine CHECK (pagine IS NULL OR pagine > 0)
);

-- ---------------------------------------------------------------------
-- libri_autori: tabella ponte per la relazione N-N
-- (un libro puo' avere piu' autori, un autore scrive piu' libri)
-- In Laravel si chiama "tabella pivot".
-- ---------------------------------------------------------------------

CREATE TABLE libri_autori (
    libro_id   INT UNSIGNED NOT NULL,
    autore_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (libro_id, autore_id),            -- chiave primaria composta

    CONSTRAINT fk_la_libro FOREIGN KEY (libro_id)
        REFERENCES libri(id) ON DELETE CASCADE,   -- elimino il libro -> spariscono i collegamenti
    CONSTRAINT fk_la_autore FOREIGN KEY (autore_id)
        REFERENCES autori(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------
-- prestiti: collega soci e libri, con dati propri (le date)
-- data_restituzione NULL = libro ancora fuori
-- ---------------------------------------------------------------------

CREATE TABLE prestiti (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libro_id           INT UNSIGNED NOT NULL,
    socio_id           INT UNSIGNED NOT NULL,
    data_prestito      DATE NOT NULL,
    data_restituzione  DATE NULL,

    CONSTRAINT fk_prestiti_libro FOREIGN KEY (libro_id)
        REFERENCES libri(id) ON DELETE RESTRICT,  -- non elimino libri con storico prestiti
    CONSTRAINT fk_prestiti_socio FOREIGN KEY (socio_id)
        REFERENCES soci(id) ON DELETE RESTRICT,

    CONSTRAINT chk_date CHECK (data_restituzione IS NULL OR data_restituzione >= data_prestito)
);

-- Indici sulle colonne usate spesso nelle ricerche
CREATE INDEX idx_libri_titolo ON libri (titolo);
CREATE INDEX idx_autori_cognome ON autori (cognome);
CREATE INDEX idx_prestiti_date ON prestiti (data_prestito, data_restituzione);
