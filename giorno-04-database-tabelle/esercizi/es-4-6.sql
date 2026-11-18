
CREATE DATABASE IF NOT EXISTS palestra;
USE palestra;


CREATE TABLE iscritti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    telefono VARCHAR(20)
);

CREATE TABLE istruttori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    specializzazione VARCHAR(50)
);

CREATE TABLE sale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_sala VARCHAR(50) NOT NULL,
    capienza INT NOT NULL
);

CREATE TABLE corsi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_corso VARCHAR(100) NOT NULL,
    istruttore_id INT NOT NULL,
    sala_id INT NOT NULL,
    FOREIGN KEY (istruttore_id) REFERENCES istruttori(id) ON DELETE CASCADE,
    FOREIGN KEY (sala_id) REFERENCES sale(id) ON DELETE CASCADE
);

CREATE TABLE iscrizioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    iscritto_id INT NOT NULL,
    corso_id INT NOT NULL,
    data_inizio DATE NOT NULL,
    pagamento_saldato BOOLEAN NOT NULL DEFAULT FALSE, 
    FOREIGN KEY (iscritto_id) REFERENCES iscritti(id) ON DELETE CASCADE,
    FOREIGN KEY (corso_id) REFERENCES corsi(id) ON DELETE CASCADE
);


INSERT INTO iscritti (nome, cognome, telefono) VALUES 
    ('Luca', 'Neri', '3331122334'),
    ('Martina', 'Gialli', '3389988776'),
    ('Davide', 'Conti', '3405544332');

INSERT INTO istruttori (nome, cognome, specializzazione) VALUES 
    ('Roberto', 'Baggio', 'Calcio / Functional'),
    ('Federica', 'Pellegrini', 'Nuoto / Fitness'),
    ('Juri', 'Chechi', 'Calisthenics / Corpo Libero');

INSERT INTO sale (nome_sala, capienza) VALUES 
    ('Sala A - Pesistica', 25),
    ('Sala B - Fitness', 15),
    ('Sala C - Corsi Musicali', 30);

INSERT INTO corsi (nome_corso, istruttore_id, sala_id) VALUES 
    ('CrossTraining', 1, 1),  
    ('AcquaGym', 2, 2),        
    ('Pilates', 3, 3);    

INSERT INTO iscrizioni (iscritto_id, corso_id, data_inizio, pagamento_saldato) VALUES 
    (1, 1, '2026-11-01', TRUE),   
    (1, 2, '2026-11-01', FALSE),  
    (2, 2, '2026-11-05', TRUE),   
    (3, 3, '2026-11-10', TRUE);   