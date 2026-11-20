-- =====================================================================
-- Giorno 5 - soluzioni
-- =====================================================================
USE biblioteca;

-- ---------------------------------------------------------------------
-- Livello 1 – Una tabella (40 minuti)
-- ---------------------------------------------------------------------

-- 5.1. Tutti i libri ordinati per anno, dal piu' recente
SELECT titolo, anno FROM libri ORDER BY anno DESC;

-- 5.2. I libri pubblicati tra il 1960 e il 1975 (estremi inclusi)
SELECT titolo, anno FROM libri WHERE anno BETWEEN 1960 AND 1975 ORDER BY anno;

-- 5.3. I libri il cui titolo contiene la parola "il" (maiuscole o minuscole)
SELECT titolo FROM libri WHERE titolo LIKE '%il %' OR titolo LIKE 'il %';

-- 5.4. I 3 libri piu' lunghi
SELECT titolo, pagine FROM libri ORDER BY pagine DESC LIMIT 3;

-- 5.5. I libri senza numero di pagine
SELECT titolo FROM libri WHERE pagine IS NULL;     -- "= NULL" NON funziona

-- 5.6. Le nazionalita' degli autori, senza ripetizioni
SELECT DISTINCT nazionalita FROM autori ORDER BY nazionalita;

-- =====================================================================
-- LIVELLO 2 - Funzioni di aggregazione e GROUP BY
-- =====================================================================

-- ---------------------------------------------------------------------
-- Livello 2 – Contare (30 minuti)
-- ---------------------------------------------------------------------

-- 5.7. Quanti libri ci sono in catalogo
SELECT COUNT(*) AS totale_libri FROM libri;

-- 5.8. Pagine medie, minime e massime
SELECT ROUND(AVG(pagine), 1) AS media, MIN(pagine) AS minimo, MAX(pagine) AS massimo
FROM libri;                                         -- AVG ignora i NULL

-- 5.9. Quanti libri per ogni genere (usando solo l'id del genere)
SELECT genere_id, COUNT(*) AS n_libri
FROM libri
GROUP BY genere_id;

-- 5.10. Gli anni in cui sono usciti almeno 2 libri
SELECT anno, COUNT(*) AS n_libri
FROM libri
GROUP BY anno
HAVING COUNT(*) >= 2;                               -- WHERE filtra righe, HAVING filtra gruppi

-- =====================================================================
-- LIVELLO 3 - JOIN
-- =====================================================================

-- ---------------------------------------------------------------------
-- Livello 3 – JOIN (70 minuti)
-- ---------------------------------------------------------------------

-- 5.11. Titolo del libro e nome dell'editore
SELECT l.titolo, e.nome AS editore
FROM libri l
JOIN editori e ON e.id = l.editore_id;             -- il libro 16 (senza editore) non compare

-- 5.12. Come la 11, ma includendo anche i libri senza editore
SELECT l.titolo, e.nome AS editore
FROM libri l
LEFT JOIN editori e ON e.id = l.editore_id;

-- 5.13. Numero di libri per genere, con il NOME del genere, compresi i generi vuoti
SELECT g.nome AS genere, COUNT(l.id) AS n_libri    -- COUNT(l.id), non COUNT(*): conta 0 per i generi vuoti
FROM generi g
LEFT JOIN libri l ON l.genere_id = g.id
GROUP BY g.id, g.nome
ORDER BY n_libri DESC;

-- 5.14. Titolo e autore/i di ogni libro (relazione N-N: due JOIN)
SELECT l.titolo, CONCAT(a.nome, ' ', a.cognome) AS autore
FROM libri l
JOIN libri_autori la ON la.libro_id = l.id
JOIN autori a        ON a.id = la.autore_id
ORDER BY l.titolo;

-- 5.15. Gli autori che non hanno libri in catalogo
SELECT a.nome, a.cognome
FROM autori a
LEFT JOIN libri_autori la ON la.autore_id = a.id
WHERE la.libro_id IS NULL;

-- 5.16. I soci che non hanno mai preso un libro in prestito
SELECT s.nome, s.cognome
FROM soci s
LEFT JOIN prestiti p ON p.socio_id = s.id
WHERE p.id IS NULL;

-- 5.17. Prestiti ancora aperti: socio, titolo, data e giorni trascorsi
SELECT CONCAT(s.nome, ' ', s.cognome) AS socio,
       l.titolo,
       p.data_prestito,
       DATEDIFF(CURRENT_DATE, p.data_prestito) AS giorni
FROM prestiti p
JOIN soci s  ON s.id = p.socio_id
JOIN libri l ON l.id = p.libro_id
WHERE p.data_restituzione IS NULL
ORDER BY p.data_prestito;

-- =====================================================================
-- LIVELLO 4 - JOIN + aggregazioni
-- =====================================================================

-- ---------------------------------------------------------------------
-- Livello 4 – JOIN e conteggi insieme (40 minuti)
-- ---------------------------------------------------------------------

-- 5.18. Classifica dei libri piu' prestati
SELECT l.titolo, COUNT(p.id) AS n_prestiti
FROM libri l
JOIN prestiti p ON p.libro_id = l.id
GROUP BY l.id, l.titolo
ORDER BY n_prestiti DESC, l.titolo
LIMIT 5;

-- 5.19. Per ogni socio: numero di prestiti e durata media (solo prestiti chiusi)
SELECT CONCAT(s.nome, ' ', s.cognome) AS socio,
       COUNT(p.id) AS prestiti_chiusi,
       ROUND(AVG(DATEDIFF(p.data_restituzione, p.data_prestito)), 1) AS giorni_medi
FROM soci s
JOIN prestiti p ON p.socio_id = s.id
WHERE p.data_restituzione IS NOT NULL
GROUP BY s.id, s.nome, s.cognome
ORDER BY giorni_medi DESC;

-- ---------------------------------------------------------------------
-- Livello 5 – Modificare e i vincoli (30 minuti)
-- ---------------------------------------------------------------------

-- 5.20. Registrare la restituzione del prestito aperto di "Il nome della rosa"
UPDATE prestiti
SET data_restituzione = CURRENT_DATE
WHERE libro_id = 8 AND data_restituzione IS NULL;
-- Prima di ogni UPDATE/DELETE: scrivere la stessa WHERE in una SELECT e controllare le righe.

-- 5.21. Provare a eliminare il genere "Giallo": cosa succede e perche'?
-- DELETE FROM generi WHERE nome = 'Giallo';
-- -> errore: vincolo fk_libri_genere con ON DELETE RESTRICT

-- 5.22. Eliminare l'editore Sellerio (non ha libri) e poi Adelphi: cosa succede ai libri di Adelphi?
DELETE FROM editori WHERE nome = 'Sellerio';
DELETE FROM editori WHERE nome = 'Adelphi';
SELECT titolo, editore_id FROM libri WHERE id IN (11, 12);   -- editore_id e' diventato NULL (ON DELETE SET NULL)

-- Extra
-- 30. Vista con i prestiti aperti, riutilizzabile come una tabella
CREATE OR REPLACE VIEW v_prestiti_aperti AS
SELECT p.id, CONCAT(s.nome, ' ', s.cognome) AS socio, l.titolo, p.data_prestito
FROM prestiti p
JOIN soci s  ON s.id = p.socio_id
JOIN libri l ON l.id = p.libro_id
WHERE p.data_restituzione IS NULL;

SELECT * FROM v_prestiti_aperti ORDER BY data_prestito;
