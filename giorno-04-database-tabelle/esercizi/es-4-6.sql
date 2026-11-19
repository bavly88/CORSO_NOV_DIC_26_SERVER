CREATE DATABASE palestra;

USE palestra;

CREATE TABLE istruttori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    specializzazione VARCHAR(100)
);

CREATE TABLE sale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    capienza INT NOT NULL
);

CREATE TABLE iscritti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);


CREATE TABLE corsi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    orario VARCHAR(50) NOT NULL,
    id_istruttore INT NOT NULL,
    FOREIGN KEY (id_istruttore) REFERENCES istruttori(id)
);

CREATE TABLE corsi_sale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_corso INT NOT NULL,
    id_sala INT NOT NULL,
    FOREIGN KEY (id_corso) REFERENCES corsi(id),
    FOREIGN KEY (id_sala) REFERENCES sale(id)
);

CREATE TABLE iscrizioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_iscritto INT NOT NULL,
    id_corso INT NOT NULL,
    data_inizio DATE NOT NULL,
    saldato BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (id_iscritto) REFERENCES iscritti(id),
    FOREIGN KEY (id_corso) REFERENCES corsi(id)
);


INSERT INTO istruttori (nome, cognome, specializzazione) VALUES
('Marco', 'Bianchi', 'Functional Training'),
('Laura', 'Verdi', 'Yoga e Pilates'),
('Davide', 'Neri', 'Boxe e Arti Marziali');

INSERT INTO sale (nome, capienza) VALUES
('Sala Pesi', 30),
('Sala Olistica', 15),
('Sala Ring', 20);

INSERT INTO iscritti (nome, cognome, email) VALUES
('Giulia', 'Rossi', 'giulia.rossi@email.it'),
('Luca', 'Ferri', 'luca.ferri@email.it'),
('Sara', 'Conti', 'sara.conti@email.it');

INSERT INTO corsi (nome, orario, id_istruttore) VALUES
('CrossFit Base', 'Lun-Mer 18:00', 1),
('Hatha Yoga', 'Mar-Gio 10:00', 2),
('Fit Boxe', 'Ven 19:00', 3);

INSERT INTO corsi_sale (id_corso, id_sala) VALUES
(1, 1),
(1, 3),
(2, 2),
(3, 3);

INSERT INTO iscrizioni (id_iscritto, id_corso, data_inizio, saldato) VALUES
(1, 1, '2026-10-01', TRUE),
(1, 2, '2026-10-05', TRUE),
(2, 3, '2026-11-01', FALSE),
(3, 2, '2026-11-10', TRUE);