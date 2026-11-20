-- =====================================================================
-- 04 - MODIFICARE E CANCELLARE
-- Per ripartire dai dati originali: rieseguire i due file 00.
-- =====================================================================
USE biblioteca;

-- UPDATE: modificare righe esistenti
UPDATE libri SET pagine = 250 WHERE id = 16;

-- Restituzione di un prestito (in questo caso nessun record sarà aggiornato)
UPDATE prestiti SET data_restituzione = CURRENT_DATE
WHERE libro_id = 8 AND data_restituzione IS NULL;

-- DELETE: cancellare righe
DELETE FROM soci WHERE id = 8;   -- Davide Colombo, nessun prestito: si può

-- ATTENZIONE: UPDATE e DELETE senza WHERE agiscono su TUTTE le righe.
-- Prima di eseguirli, scrivere la stessa condizione in un SELECT e controllare il risultato:
SELECT * FROM soci WHERE id = 8;

-- I vincoli delle chiavi esterne in azione (eseguire una riga alla volta):
-- DELETE FROM generi WHERE nome = 'Giallo';
--   errore: ci sono libri gialli e la chiave esterna è ON DELETE RESTRICT
-- DELETE FROM libri WHERE id = 13;
--   errore: il libro ha dei prestiti (RESTRICT). Le sue righe in libri_autori
--   invece sparirebbero da sole (CASCADE).
-- DELETE FROM editori WHERE nome = 'Adelphi';
--   funziona: i libri di Adelphi restano, con editore_id NULL (SET NULL)
