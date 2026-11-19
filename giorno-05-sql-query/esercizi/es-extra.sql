USE biblioteca;



CREATE OR REPLACE VIEW v_prestiti_aperti AS
SELECT
    s.nome AS nome_socio,
    s.cognome AS cognome_socio,
    l.titolo AS titolo_libro,
    p.data_prestito
FROM prestiti AS p
JOIN soci AS s ON p.socio_id = s.id
JOIN libri AS l ON p.libro_id = l.id
WHERE p.data_restituzione IS NULL;

SELECT * FROM v_prestiti_aperti;