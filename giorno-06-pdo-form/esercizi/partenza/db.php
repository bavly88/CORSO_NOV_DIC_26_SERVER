<?php
// Funzioni comuni a tutte le pagine del catalogo.

// db(): la connessione, creata una volta sola per richiesta.
// static: la variabile mantiene il valore tra una chiamata e l'altra.
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $c = require __DIR__ . '/config.php';
        $pdo = new PDO(
            "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset=utf8mb4",
            $c['username'],
            $c['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function e(?string $testo): string
{
    return htmlspecialchars($testo ?? '', ENT_QUOTES, 'UTF-8');
}
