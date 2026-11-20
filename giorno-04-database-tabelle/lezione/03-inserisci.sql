-- =====================================================================
-- 03 - INSERIRE DATI
-- Eseguire dopo 02-crea-tabelle.sql
-- =====================================================================
USE lezione_sql;

-- Una riga: l'id non si scrive, lo assegna AUTO_INCREMENT
INSERT INTO generi (nome) VALUES ('Romanzo');

-- Più righe insieme
INSERT INTO generi (nome) VALUES ('Racconti'), ('Giallo'), ('Saggio');

-- Le stringhe vanno tra apici singoli. Un apostrofo dentro il testo si raddoppia: ''
INSERT INTO autori (nome, cognome) VALUES
('Italo', 'Calvino'),
('Umberto', 'Eco'),
('Carlo', 'Fruttero'),
('Franco', 'Lucentini');

-- genere_id: l'id di un genere che esiste (Romanzo = 1, Giallo = 3)
<<<<<<< HEAD
libri

-- Collegare libri e autori: una riga per ogni coppia
INSERT INTO libri_autori (libro_id, autore_id) VALUES
(19, 5),   -- Il barone rampante - Calvino
(20, 6),   -- Il nome della rosa - Eco
(21, 7),   -- La donna della domenica - Fruttero
(21, 8);   -- La donna della domenica - Lucentini
=======

-- PROVE DI ERRORE (togliere il commento una alla volta ed eseguire con Ctrl+F9):
-- INSERT INTO generi (nome) VALUES ('Giallo');              -- doppione: UNIQUE lo rifiuta
-- INSERT INTO libri (titolo, genere_id) VALUES ('X', 99);    -- il genere 99 non esiste: FOREIGN KEY
-- INSERT INTO libri (anno) VALUES (2000);                    -- manca il titolo: NOT NULL
