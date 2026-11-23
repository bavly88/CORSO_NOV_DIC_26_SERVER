<?php
// Funzioni comuni a tutte le pagine del catalogo.
// Rispetto al giorno 6: session_start() e le funzioni della lezione di oggi (in fondo).
session_start();

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

function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

// ---------- utente autenticato ----------

function utenteCorrente(): ?array
{
    return $_SESSION['utente'] ?? null;   // salvato in sessione al login
}

// Da chiamare in cima alle pagine riservate
function richiediLogin(): array
{
    $utente = utenteCorrente();
    if ($utente === null) {
        redirect('login.php');
    }
    return $utente;
}

// ---------- CSRF ----------
// Un codice casuale salvato in sessione e inserito in ogni form.
// Una pagina di un altro sito non lo conosce, quindi non può inviare il form al posto dell'utente.

function csrfToken(): string
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfCampo(): string
{
    return '<input type="hidden" name="csrf" value="' . csrfToken() . '">';
}

function csrfVerifica(): void
{
    if (!hash_equals(csrfToken(), $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Richiesta non valida (token CSRF mancante o sbagliato).');
    }
}
