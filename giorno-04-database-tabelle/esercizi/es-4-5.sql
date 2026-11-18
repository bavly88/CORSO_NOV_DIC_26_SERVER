

SELECT * 
FROM libri 
ORDER BY titolo ASC;


SELECT titolo, anno 
FROM libri 
WHERE anno >= 1960;


SELECT * 
FROM libri 
ORDER BY pagine DESC 
LIMIT 3;


SELECT * 
FROM libri 
WHERE genere_id = 1;

SELECT * 
FROM autori 
ORDER BY cognome, nome;

SELECT * 
FROM prestiti 
WHERE data_restituzione IS NULL;