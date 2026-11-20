-- Esercizio 4.6 - Una palestra
-- Relazioni:
--   istruttore 1 - N corsi     (un corso ha un solo istruttore)
--   sala       1 - N corsi     (un corso si tiene in una sala)
--   iscritti   N - N corsi     -> tabella ponte iscrizioni, con data_inizio e pagato
DROP DATABASE IF EXISTS palestra;
CREATE DATABASE palestra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE palestra;

CREATE TABLE istruttori (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome     VARCHAR(60) NOT NULL,
    cognome  VARCHAR(60) NOT NULL
);

CREATE TABLE sale (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(50) NOT NULL UNIQUE,
    capienza   SMALLINT UNSIGNED NOT NULL
);

CREATE TABLE corsi (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(80) NOT NULL,
    istruttore_id  INT UNSIGNED NOT NULL,
    sala_id        INT UNSIGNED NOT NULL,
    FOREIGN KEY (istruttore_id) REFERENCES istruttori(id),
    FOREIGN KEY (sala_id) REFERENCES sale(id)
);

CREATE TABLE iscritti (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome     VARCHAR(60) NOT NULL,
    cognome  VARCHAR(60) NOT NULL,
    email    VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE iscrizioni (
    iscritto_id  INT UNSIGNED NOT NULL,
    corso_id     INT UNSIGNED NOT NULL,
    data_inizio  DATE NOT NULL,
    pagato       BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (iscritto_id, corso_id),
    FOREIGN KEY (iscritto_id) REFERENCES iscritti(id) ON DELETE CASCADE,
    FOREIGN KEY (corso_id) REFERENCES corsi(id) ON DELETE CASCADE
);

INSERT INTO istruttori (nome, cognome) VALUES ('Luca', 'Neri'), ('Elena', 'Gallo'), ('Paolo', 'Riva');
INSERT INTO sale (nome, capienza) VALUES ('Sala A', 20), ('Sala B', 12), ('Piscina', 30);
INSERT INTO corsi (nome, istruttore_id, sala_id) VALUES ('Yoga', 2, 2), ('Pilates', 2, 1), ('Nuoto', 3, 3);
INSERT INTO iscritti (nome, cognome, email) VALUES
('Anna', 'Rossi', 'anna@example.com'), ('Marco', 'Bianchi', 'marco@example.com'), ('Sara', 'Verdi', 'sara@example.com');
INSERT INTO iscrizioni (iscritto_id, corso_id, data_inizio, pagato) VALUES
(1, 1, '2026-10-01', TRUE), (1, 3, '2026-10-15', FALSE), (2, 2, '2026-11-02', TRUE), (3, 1, '2026-11-03', FALSE);
