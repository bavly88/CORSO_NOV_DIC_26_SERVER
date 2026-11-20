-- Esercizio 4.4 - Il catalogo completo
-- Dopo 03-inserisci.sql gli autori 1-4 sono Calvino, Eco, Fruttero, Lucentini
-- e i libri 1-4 esistono già. I nuovi id sono quindi 5-6 per gli autori e 5-9 per i libri.
USE lezione_sql;

INSERT INTO autori (nome, cognome) VALUES
('Primo', 'Levi'),            -- 5
('Leonardo', 'Sciascia');     -- 6

-- generi: 1 Romanzo, 2 Racconti, 3 Giallo, 4 Saggio
INSERT INTO libri (titolo, anno, pagine, genere_id) VALUES
('Le città invisibili', 1972, 168, 1),     -- 5
('Marcovaldo', 1963, 160, 2),              -- 6
('Il pendolo di Foucault', 1988, 509, 1),  -- 7
('Se questo è un uomo', 1947, 220, 4),     -- 8
('Il giorno della civetta', 1961, 130, 3); -- 9

INSERT INTO libri_autori (libro_id, autore_id) VALUES
(5, 1), (6, 1), (7, 2), (8, 5), (9, 6);
