-- =====================================================================
-- 02 - CREARE DATABASE E TABELLE
-- In HeidiSQL (utente corso): File > Carica file SQL, poi F9.
-- Dopo l'esecuzione: F5 nell'elenco a sinistra.
-- =====================================================================

-- Il database della lezione. DROP lo cancella se esiste già: lo script si può rieseguire.
DROP DATABASE IF EXISTS lezione_sql;
CREATE DATABASE lezione_sql CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lezione_sql;   -- da qui in poi i comandi lavorano su questo database

-- generi: una tabella "semplice"
CREATE TABLE generi (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- numero assegnato dal database
    nome  VARCHAR(50) NOT NULL UNIQUE               -- testo fino a 50 caratteri, obbligatorio, senza doppioni
);

CREATE TABLE autori (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome     VARCHAR(60) NOT NULL,
    cognome  VARCHAR(60) NOT NULL
);

-- libri: ha una chiave esterna verso generi (relazione 1-N)
CREATE TABLE libri (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titolo     VARCHAR(200) NOT NULL,
    anno       SMALLINT NULL,                         -- NULL: il valore può mancare
    pagine     SMALLINT UNSIGNED NULL,                 -- UNSIGNED: niente numeri negativi
    genere_id  INT UNSIGNED NULL,

    FOREIGN KEY (genere_id) REFERENCES generi(id)
);

-- libri_autori: tabella ponte (relazione N-N)
generiCREATE TABLE libri_autori (
    libro_id   INT UNSIGNED NOT NULL,
    autore_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (libro_id, autore_id),               -- la coppia non si può ripetere

    FOREIGN KEY (libro_id) REFERENCES libri(id) ON DELETE CASCADE,
    FOREIGN KEY (autore_id) REFERENCES autori(id) ON DELETE CASCADE
    -- ON DELETE CASCADE: se cancello un libro, spariscono anche le sue righe qui
);

-- I tipi più usati:
--   INT, SMALLINT          numeri interi
--   DECIMAL(8,2)           numeri con decimali esatti (prezzi)
--   VARCHAR(n)             testo breve, al massimo n caratteri
--   TEXT                   testo lungo
--   DATE, DATETIME         data, data e ora
--   BOOLEAN                vero/falso (in MariaDB è un TINYINT: 0 o 1)
