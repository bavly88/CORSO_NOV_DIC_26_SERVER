USE biblioteca;


SELECT
    l.titolo,
    e.nome AS editore
FROM libri AS l
INNER JOIN editori AS e ON l.editore_id = e.id;



SELECT
    l.titolo,
    e.nome AS editore
FROM libri AS l
LEFT JOIN editori AS e ON l.editore_id = e.id;



SELECT
    g.nome AS genere,
    COUNT(l.id) AS numero_libri
FROM generi AS g
LEFT JOIN libri AS l ON g.id = l.genere_id
GROUP BY g.id, g.nome;



SELECT
    l.titolo,
    a.nome,
    a.cognome
FROM libri AS l
JOIN libri_autori AS la ON l.id = la.libro_id
JOIN autori AS a ON la.autore_id = a.id;



SELECT
    a.id,
    a.nome,
    a.cognome
FROM autori AS a
LEFT JOIN libri_autori AS la ON a.id = la.autore_id
LEFT JOIN libri AS l ON la.libro_id = l.id
WHERE l.id IS NULL;



SELECT
    s.id,
    s.nome,
    s.cognome
FROM soci AS s
LEFT JOIN prestiti AS p ON s.id = p.socio_id
WHERE p.id IS NULL;



SELECT
    s.nome AS nome_socio,
    s.cognome AS cognome_socio,
    l.titolo AS titolo_libro,
    p.data_prestito,
    DATEDIFF(CURDATE(), p.data_prestito) AS giorni_trascorsi
FROM prestiti AS p
JOIN soci AS s ON p.socio_id = s.id
JOIN libri AS l ON p.libro_id = l.id
WHERE p.data_restituzione IS NULL;