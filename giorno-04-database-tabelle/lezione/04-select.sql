-- =====================================================================
-- 04 - LEGGERE I DATI: SELECT
-- =====================================================================
USE lezione_sql;

-- Tutte le colonne, tutte le righe
SELECT * FROM libri;

-- Solo alcune colonne
SELECT titolo, anno FROM libri;

-- WHERE: solo le righe che rispettano la condizione
SELECT titolo, anno FROM libri WHERE anno < 1970;
SELECT * FROM libri WHERE genere_id = 3;

-- ORDER BY: ordinare (ASC crescente, il default; DESC decrescente)
SELECT titolo, anno FROM libri ORDER BY anno DESC;

-- LIMIT: solo le prime righe
SELECT titolo, pagine FROM libri ORDER BY pagine DESC LIMIT 2;

-- Valori mancanti: IS NULL (non "= NULL", che non funziona)
SELECT titolo FROM libri WHERE pagine IS NULL;

-- Domani: condizioni più ricche, JOIN tra tabelle, conteggi, modifiche e cancellazioni.
