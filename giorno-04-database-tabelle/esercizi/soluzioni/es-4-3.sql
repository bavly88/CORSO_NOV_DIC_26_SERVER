-- Esercizio 4.3 - Soci e prestiti
USE lezione_sql;

CREATE TABLE soci (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome             VARCHAR(60)  NOT NULL,
    cognome          VARCHAR(60)  NOT NULL,
    email            VARCHAR(150) NOT NULL UNIQUE,
    data_iscrizione  DATE         NOT NULL DEFAULT (CURRENT_DATE)
);

CREATE TABLE prestiti (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libro_id           INT UNSIGNED NOT NULL,
    socio_id           INT UNSIGNED NOT NULL,
    data_prestito      DATE NOT NULL,
    data_restituzione  DATE NULL,
    FOREIGN KEY (libro_id) REFERENCES libri(id),
    FOREIGN KEY (socio_id) REFERENCES soci(id)
);

INSERT INTO soci (nome, cognome, email) VALUES
('Anna', 'Rossi', 'anna@example.com'),
('Marco', 'Bianchi', 'marco@example.com'),
('Sara', 'Verdi', 'sara@example.com');

INSERT INTO prestiti (libro_id, socio_id, data_prestito, data_restituzione) VALUES
(2, 1, '2026-10-02', '2026-10-20'),
(3, 1, '2026-10-15', NULL),
(2, 2, '2026-10-21', '2026-11-03'),
(1, 3, '2026-11-02', NULL);

-- 4. Il socio 99 non esiste:
-- INSERT INTO prestiti (libro_id, socio_id, data_prestito) VALUES (1, 99, '2026-11-06');
-- ERROR 1452: Cannot add or update a child row: a foreign key constraint fails
