USE biblioteca;



SELECT
    l.titolo,
    COUNT(p.id) AS numero_prestiti
FROM libri AS l
JOIN prestiti AS p ON l.id = p.libro_id
GROUP BY l.id, l.titolo
ORDER BY numero_prestiti DESC
LIMIT 5;



SELECT
    s.nome,
    s.cognome,
    COUNT(p.id) AS prestiti_restituiti,
    AVG(DATEDIFF(p.data_restituzione, p.data_prestito)) AS durata_media_giorni
FROM soci AS s
JOIN prestiti AS p ON s.id = p.socio_id
WHERE p.data_restituzione IS NOT NULL
GROUP BY s.id, s.nome, s.cognome;

