<?php
/*
 * 01 - CONNETTERSI AL DATABASE CON PDO
 * Esecuzione:  php 01-connessione.php
 * Serve l'estensione pdo_mysql attiva nel php.ini (php -m per controllare).
 *
 * PDO (PHP Data Objects) è la classe di PHP per parlare con un database.
 */
$config = require __DIR__ . '/config.php';

// DSN: tipo di database, server, porta, nome del database, set di caratteri
$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4"; //mb4 =multi-byte, 4: ogni carattere può occupare al massimo 4 byte

$pdo = new PDO($dsn, $config['username'], $config['password'], [
    //PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION: cosa fa PDO quando una query fallisce (tabella che non esiste, errore di sintassi, vincolo violato).Con ERRMODE_EXCEPTION PDO lancia un’eccezione (PDOException): lo script si ferma e mostra l’errore, a meno che tu non lo gestisca con try/catch.

    //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC:Cosa imposta: in che forma ricevi le righe quando leggi i risultati con fetch() o fetchAll(). Con FETCH_ASSOC ogni riga è un array associativo: le chiavi sono i nomi delle colonne.
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     
]);
echo "Connesso!\n";

// query(): esegue un SELECT senza parti variabili
$risultato = $pdo->query("SELECT id, titolo, anno FROM libri ORDER BY titolo LIMIT 5");

// fetchAll(): tutte le righe, come array di array associativi (la forma del giorno 2)
$libri = $risultato->fetchAll();
foreach ($libri as $libro) {
    echo "{$libro['id']} - {$libro['titolo']} ({$libro['anno']})\n";
}

// fetch(): una riga sola
$conteggio = $pdo->query("SELECT COUNT(*) AS quanti FROM libri")->fetch();
echo "Libri in catalogo: {$conteggio['quanti']}\n";

// Prova: cambia la password in config.php. Che errore compare?
