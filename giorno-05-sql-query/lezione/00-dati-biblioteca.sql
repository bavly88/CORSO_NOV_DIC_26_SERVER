-- =====================================================================
-- Dati di esempio per il database "biblioteca"
-- Eseguire DOPO 00-schema-biblioteca.sql
-- =====================================================================

-- Il file contiene lettere accentate: il client deve leggerlo in UTF-8
SET NAMES utf8mb4;

USE biblioteca;

INSERT INTO editori (id, nome, citta) VALUES
(1, 'Einaudi', 'Torino'),
(2, 'Mondadori', 'Milano'),
(3, 'Bompiani', 'Milano'),
(4, 'Feltrinelli', 'Milano'),
(5, 'Adelphi', 'Milano'),
(6, 'Sellerio', 'Palermo');          -- nessun libro: utile per i LEFT JOIN

INSERT INTO generi (id, nome) VALUES
(1, 'Romanzo'),
(2, 'Racconti'),
(3, 'Saggio'),
(4, 'Giallo'),
(5, 'Poesia');                        -- nessun libro

INSERT INTO autori (id, nome, cognome, nazionalita, anno_nascita) VALUES
(1, 'Italo', 'Calvino', 'italiana', 1923),
(2, 'Elsa', 'Morante', 'italiana', 1912),
(3, 'Primo', 'Levi', 'italiana', 1919),
(4, 'Umberto', 'Eco', 'italiana', 1932),
(5, 'Natalia', 'Ginzburg', 'italiana', 1916),
(6, 'Leonardo', 'Sciascia', 'italiana', 1921),
(7, 'Carlo', 'Fruttero', 'italiana', 1926),
(8, 'Franco', 'Lucentini', 'italiana', 1920),
(9, 'Virginia', 'Woolf', 'britannica', 1882),
(10, 'Jorge Luis', 'Borges', 'argentina', 1899);   -- nessun libro in catalogo

INSERT INTO libri (id, titolo, isbn, anno, pagine, editore_id, genere_id) VALUES
(1,  'Il barone rampante',            '9788804668237', 1957, 270, 2, 1),
(2,  'Le città invisibili',          '9788804668244', 1972, 168, 2, 1),
(3,  'Marcovaldo',                    '9788804668213', 1963, 160, 2, 2),
(4,  'La storia',                     '9788806219352', 1974, 660, 1, 1),
(5,  'L''isola di Arturo',            '9788806219383', 1957, 400, 1, 1),
(6,  'Il sistema periodico',          '9788806219420', 1975, 240, 1, 2),
(7,  'Se questo è un uomo',          '9788806219406', 1947, 220, 1, 3),
(8,  'Il nome della rosa',            '9788845292613', 1980, 503, 3, 4),
(9,  'Il pendolo di Foucault',        '9788845292620', 1988, 509, 3, 1),
(10, 'Lessico famigliare',            '9788806219437', 1963, 230, 1, 1),
(11, 'Il giorno della civetta',       '9788845928475', 1961, 130, 5, 4),
(12, 'A ciascuno il suo',             '9788845928482', 1966, 160, 5, 4),
(13, 'La donna della domenica',       '9788804668251', 1972, 450, 2, 4),
(14, 'Gita al faro',                  '9788807900099', 1927, 230, 4, 1),
(15, 'Una stanza tutta per sé',      '9788807900105', 1929, 160, 4, 3),
(16, 'Manuale senza editore',         NULL,            2020, NULL, NULL, 3);

INSERT INTO libri_autori (libro_id, autore_id) VALUES
(1, 1), (2, 1), (3, 1),
(4, 2), (5, 2),
(6, 3), (7, 3),
(8, 4), (9, 4),
(10, 5),
(11, 6), (12, 6),
(13, 7), (13, 8),          -- libro con DUE autori
(14, 9), (15, 9);
-- il libro 16 non ha autori: utile per i LEFT JOIN

INSERT INTO soci (id, nome, cognome, email, data_iscrizione) VALUES
(1, 'Anna',    'Rossi',    'anna.rossi@example.com',    '2024-09-10'),
(2, 'Luca',    'Bianchi',  'luca.bianchi@example.com',  '2025-01-15'),
(3, 'Sara',    'Verdi',    'sara.verdi@example.com',    '2025-03-02'),
(4, 'Marco',   'Neri',     'marco.neri@example.com',    '2025-06-20'),
(5, 'Giulia',  'Russo',    'giulia.russo@example.com',  '2025-11-05'),
(6, 'Paolo',   'Ferrari',  'paolo.ferrari@example.com', '2026-02-11'),
(7, 'Chiara',  'Esposito', 'chiara.esposito@example.com','2026-05-30'),
(8, 'Davide',  'Colombo',  'davide.colombo@example.com','2026-09-01');  -- nessun prestito

INSERT INTO prestiti (libro_id, socio_id, data_prestito, data_restituzione) VALUES
(8,  1, '2026-01-10', '2026-01-28'),
(4,  1, '2026-02-03', '2026-03-01'),
(1,  2, '2026-02-15', '2026-02-27'),
(11, 2, '2026-03-04', '2026-03-12'),
(12, 2, '2026-03-13', '2026-03-20'),
(8,  3, '2026-03-22', '2026-04-15'),
(7,  3, '2026-05-02', '2026-05-20'),
(2,  4, '2026-05-10', '2026-05-18'),
(13, 4, '2026-06-01', '2026-06-25'),
(8,  5, '2026-06-05', '2026-06-30'),
(10, 5, '2026-07-01', '2026-07-21'),
(14, 6, '2026-07-15', '2026-08-02'),
(3,  1, '2026-09-01', '2026-09-10'),
(9,  2, '2026-09-12', NULL),          -- ancora in prestito
(5,  3, '2026-09-20', NULL),          -- ancora in prestito
(11, 6, '2026-10-01', NULL),          -- ancora in prestito
(6,  7, '2026-10-05', '2026-10-15'),
(8,  7, '2026-10-16', NULL),          -- ancora in prestito
(1,  4, '2026-10-18', NULL),          -- ancora in prestito
(15, 5, '2026-10-20', '2026-10-22');
