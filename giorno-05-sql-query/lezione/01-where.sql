-- =====================================================================
-- 01 - CONDIZIONI E ORDINAMENTO
-- Prima: eseguire 00-schema-biblioteca.sql e 00-dati-biblioteca.sql
-- (database biblioteca: editori, generi, autori, libri, libri_autori, soci, prestiti)
-- =====================================================================
USE biblioteca;

-- AND, OR, NOT
SELECT titolo, anno, pagine FROM libri WHERE anno >= 1960 AND pagine < 250;
SELECT titolo, genere_id FROM libri WHERE genere_id = 2 OR genere_id = 3;

-- IN: uno tra più valori (come tanti OR)
SELECT titolo FROM libri WHERE genere_id IN (2, 3);

-- BETWEEN: in un intervallo, estremi compresi
SELECT titolo, anno FROM libri WHERE anno BETWEEN 1960 AND 1975;

-- LIKE: ricerca nel testo. % = qualsiasi sequenza di caratteri
SELECT titolo FROM libri WHERE titolo LIKE 'Il%';      -- inizia con "Il"
SELECT titolo FROM libri WHERE titolo LIKE '%rosa%';   -- contiene "rosa"
-- In MariaDB LIKE non distingue maiuscole e minuscole.

-- Valori mancanti
SELECT titolo FROM libri WHERE editore_id IS NULL;

-- DISTINCT: senza ripetizioni
SELECT DISTINCT nazionalita FROM autori;

-- Ordinamento su più colonne e alias (AS dà un nome alla colonna nel risultato)
SELECT titolo, anno AS pubblicato FROM libri ORDER BY anno DESC, titolo;
