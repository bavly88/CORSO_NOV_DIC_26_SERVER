-- =====================================================================
-- 03 - CONTARE E RAGGRUPPARE
-- =====================================================================
USE biblioteca;

-- Funzioni di aggregazione: tante righe -> un valore
SELECT COUNT(*) AS libri FROM libri;
SELECT AVG(pagine) AS media, MIN(pagine) AS minimo, MAX(pagine) AS massimo FROM libri;
SELECT SUM(pagine) AS totale FROM libri;

-- GROUP BY: un risultato per ogni gruppo
SELECT genere_id, COUNT(*) AS quanti
FROM libri
GROUP BY genere_id;

-- Con il nome del genere (JOIN + GROUP BY)
SELECT g.nome, COUNT(*) AS quanti
FROM libri l
JOIN generi g ON g.id = l.genere_id
GROUP BY g.nome
ORDER BY quanti DESC;

-- HAVING: condizione sui gruppi (WHERE filtra le righe PRIMA di raggruppare,
-- HAVING filtra i gruppi DOPO)
SELECT anno, COUNT(*) AS quanti
FROM libri
GROUP BY anno
HAVING COUNT(*) >= 2;

-- I libri più prestati
SELECT l.titolo, COUNT(*) AS prestiti
FROM prestiti p
JOIN libri l ON l.id = p.libro_id
GROUP BY l.titolo
ORDER BY prestiti DESC
LIMIT 5;
