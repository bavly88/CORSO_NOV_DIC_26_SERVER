-- Esercizio 4.5 - Primi SELECT
USE lezione_sql;

SELECT * FROM libri ORDER BY titolo;
SELECT titolo, anno FROM libri WHERE anno >= 1960;
SELECT titolo, pagine FROM libri ORDER BY pagine DESC LIMIT 3;
SELECT * FROM libri WHERE genere_id = 1;
SELECT * FROM autori ORDER BY cognome;
SELECT * FROM prestiti WHERE data_restituzione IS NULL;
