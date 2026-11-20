USE biblioteca;

SELECT *
FROM libri
ORDER BY anno DESC;

SELECT *
FROM libri
WHERE anno BETWEEN 1960 AND 1975;

SELECT *
FROM libri
WHERE titolo LIKE '%il%';

SELECT *
FROM libri
ORDER BY pagine DESC
LIMIT 3;

SELECT *
FROM libri
WHERE pagine IS NULL;

SELECT DISTINCT nazionalita
FROM autori;