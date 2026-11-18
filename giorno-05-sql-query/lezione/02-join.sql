-- =====================================================================
-- 02 - JOIN: unire più tabelle
-- =====================================================================
USE biblioteca;

-- Il problema: in libri c'è solo il numero del genere
SELECT titolo, genere_id FROM libri;

-- INNER JOIN: per ogni libro, la riga di generi con lo stesso id.
-- ON dice come si collegano le due tabelle (chiave esterna = chiave primaria).
SELECT libri.titolo, generi.nome
FROM libri
JOIN generi ON libri.genere_id = generi.id;

-- Con gli ALIAS delle tabelle (l, g) si scrive meno
SELECT l.titolo, g.nome AS genere
FROM libri l
JOIN generi g ON l.genere_id = g.id
ORDER BY g.nome, l.titolo;

-- LEFT JOIN: tutti i libri, anche quelli SENZA editore (le colonne di editori valgono NULL)
SELECT l.titolo, e.nome AS editore
FROM libri l
LEFT JOIN editori e ON l.editore_id = e.id;
-- Con JOIN normale il "Manuale senza editore" sparirebbe: provare.

-- Relazione N-N: due JOIN, passando per la tabella ponte
SELECT l.titolo, a.nome, a.cognome
FROM libri l
JOIN libri_autori la ON la.libro_id = l.id
JOIN autori a ON a.id = la.autore_id
ORDER BY l.titolo;
-- "La donna della domenica" compare due volte: ha due autori.

-- JOIN + WHERE: i libri di Calvino
SELECT l.titolo, l.anno
FROM libri l
JOIN libri_autori la ON la.libro_id = l.id
JOIN autori a ON a.id = la.autore_id
WHERE a.cognome = 'Calvino';

-- I prestiti ancora aperti, con nome del socio e titolo
SELECT s.nome, s.cognome, l.titolo, p.data_prestito
FROM prestiti p
JOIN soci s ON s.id = p.socio_id
JOIN libri l ON l.id = p.libro_id
WHERE p.data_restituzione IS NULL;
