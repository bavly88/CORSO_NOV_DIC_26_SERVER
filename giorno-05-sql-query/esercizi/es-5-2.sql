USE biblioteca;

SELECT COUNT(*) AS totale_libri
FROM libri;


SELECT
    AVG(pagine) AS media_pagine,
    MIN(pagine) AS min_pagine,
    MAX(pagine) AS max_pagine
FROM libri;


SELECT
    genere_id,
    COUNT(*) AS numero_libri
FROM libri
GROUP BY genere_id;


SELECT
    anno,
    COUNT(*) AS numero_libri
FROM libri
GROUP BY anno
HAVING COUNT(*) >= 2;



