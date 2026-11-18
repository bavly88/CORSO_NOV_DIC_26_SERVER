-- 1. Elimina prima le tabelle che contengono chiavi esterne
DROP TABLE IF EXISTS prestiti;
DROP TABLE IF EXISTS soci;
DROP TABLE IF EXISTS libri; -- Se esiste già nel database

-- 2. Ricrea le tabelle nell'ordine corretto
CREATE TABLE soci (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_iscrizione DATE DEFAULT (CURRENT_DATE)
);

CREATE TABLE prestiti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    socio_id INT NOT NULL,
    data_prestito DATE NOT NULL,
    data_restituzione DATE,
    FOREIGN KEY (libro_id) REFERENCES libri(id),
    FOREIGN KEY (socio_id) REFERENCES soci(id)
);

-- Inserimento di 3 soci (il primo sfrutta il DEFAULT per inserire la data di oggi in automatico)
INSERT INTO soci (nome, cognome, email) VALUES 
    ('Anna', 'Rossi', 'anna.rossi@example.com'),
    ('Marco', 'Bianchi', 'marco.bianchi@example.com');

-- Terzo socio con data di iscrizione personalizzata
INSERT INTO soci (nome, cognome, email, data_iscrizione) VALUES 
    ('Giulia', 'Verdi', 'giulia.verdi@example.com', '2026-01-15');

-- Inserimento di 4 prestiti (presupponendo che nella tabella 'libri' esistano almeno gli id 1, 2 e 3)
-- Nota: data_restituzione è impostata a NULL per i prestiti ancora aperti.
INSERT INTO prestiti (libro_id, socio_id, data_prestito, data_restituzione) VALUES 
    (1, 1, '2026-10-02', '2026-10-10'), -- Prestito chiuso
    (2, 1, '2026-10-15', NULL),          -- Prestito ancora aperto
    (3, 2, '2026-10-20', '2026-10-25'), -- Prestito chiuso
    (1, 3, '2026-11-01', NULL);          -- Prestito ancora aperto

/*
-- Se provi a eseguire questa istruzione:
INSERT INTO prestiti (libro_id, socio_id, data_prestito) VALUES 
    (1, 99, '2026-11-05');

--- RISPOSTA ALLE DOMANDE RICHIESTE DALL'ESERCIZIO ---

1. Messaggio di errore:
   "Error Code: 1452. Cannot add or update a child row: a foreign key constraint fails 
   (`lezione_sql`.`prestiti`, CONSTRAINT `prestiti_ibfk_2` FOREIGN KEY (`socio_id`) REFERENCES `soci` (`id`))"

2. Quale regola lo ha causato:
   Il vincolo di chiave esterna (`FOREIGN KEY (socio_id) REFERENCES soci(id)`). Il database controlla 
   che il valore inserito in `socio_id` esista effettivamente come chiave primaria nella tabella `soci`. 
   Poiché l'id 99 non esiste, il sistema blocca l'operazione.

3. Perché è utile che il database si comporti così:
   Evita la creazione di "record orfani" (in questo caso, un prestito associato a un socio fantasma). 
   Garantisce l'integrità e la coerenza dei dati, impedendo che vengano salvate informazioni 
   logicamente scorrette o incomplete nel database.
*/