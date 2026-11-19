USE biblioteca;


SELECT
    p.id AS prestito_id,
    l.titolo,
    p.data_prestito,
    p.data_restituzione
FROM prestiti AS p
JOIN libri AS l ON p.libro_id = l.id
WHERE l.titolo = 'Il nome della rosa'
  AND p.data_restituzione IS NULL;



  UPDATE prestiti
SET data_restituzione = CURDATE()
WHERE libro_id = (
    SELECT id FROM libri WHERE titolo = 'Il nome della rosa'
)
AND data_restituzione IS NULL;



-- 5.21 – Errore 1451: bloccato dalla tabella `libri` (vincolo `fk_libri_genere` su `genere_id`)
-- perché ci sono libri associati al genere 'Giallo' e la FK non ha ON DELETE CASCADE.
DELETE FROM generi
WHERE nome = 'Giallo';
-- Perché lo impedisce? Esistono uno o più libri nella tabella libri che puntano all'id del genere 'Giallo' tramite genere_id. Poiché il vincolo ha ON UPDATE CASCADE ma non ha ON DELETE CASCADE (quindi applica il comportamento predefinito RESTRICT sulla cancellazione), il database vieta di eliminare il genere per non lasciare libri con un riferimento a un genere inesistente



DELETE FROM editori
WHERE nome = 'Sellerio';



DELETE FROM editori
WHERE nome = 'Adelphi';



SELECT id, titolo, editore_id
FROM libri;


