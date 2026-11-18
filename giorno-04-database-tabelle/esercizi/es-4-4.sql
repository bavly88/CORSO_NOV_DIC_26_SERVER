
-- PASSO 0 – (Controllo preliminare in HeidiSQL)
-- Prima di eseguire, apri le tabelle 'generi' e 'autori' in HeidiSQL per verificare 
-- cosa è già presente (es. 'Saggio' nei generi). Inseriamo di seguito solo ciò che manca.

-- PASSO 1 – I generi mancanti 
-- (Inseriamo Romanzo, Racconti e Giallo se non sono già presenti)
INSERT INTO generi (nome) VALUES 
    ('Romanzo'),
    ('Racconti'),
    ('Giallo');


-- PASSO 2 – Gli autori nuovi
-- (Inseriamo gli autori che compongono il catalogo)
INSERT INTO autori (nome, cognome) VALUES 
    ('Italo', 'Calvino'),
    ('Umberto', 'Eco'),
    ('Primo', 'Levi'),
    ('Leonardo', 'Sciascia');


-- PASSO 3 – I libri
-- (Colleghiamo ogni libro al rispettivo genere_id verificato nella tabella generi)
INSERT INTO libri (titolo, anno, pagine, genere_id) VALUES 
    ('Le città invisibili', 1972, 168, 1),      -- Supponendo ID genere Romanzo = 1
    ('Marcovaldo', 1963, 160, 2),               -- Supponendo ID genere Racconti = 2
    ('Il pendolo di Foucault', 1988, 509, 1),   -- Supponendo ID genere Romanzo = 1
    ('Se questo è un uomo', 1947, 220, 3),      -- Supponendo ID genere Saggio = 3
    ('Il giorno della civetta', 1961, 130, 4);  -- Supponendo ID genere Giallo = 4


-- PASSO 4 – Le righe di 'libri_autori'
-- (Tabella ponte: associa gli id dei libri appena inseriti agli id dei rispettivi autori)
INSERT INTO libri_autori (libro_id, autore_id) VALUES
    (1, 1),   -- Le città invisibili – Italo Calvino
    (2, 1),   -- Marcovaldo – Italo Calvino
    (3, 2),   -- Il pendolo di Foucault – Umberto Eco
    (4, 3),   -- Se questo è un uomo – Primo Levi
    (5, 4);   -- Il giorno della civetta – Leonardo Sciascia