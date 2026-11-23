<?php
// Funzioni comuni a tutte le pagine del sito della lezione.

// session_start() va chiamata PRIMA di qualsiasi output (anche uno spazio),
// perché deve inviare l'header HTTP Set-Cookie con l'ID di sessione,
// e gli header possono essere inviati solo prima del corpo della pagina.
// Attenzione a: spazi/righe vuote prima di <?php, BOM UTF-8,
// echo di debug, e ? > finale seguito da a capo nei file inclusi.
// altrimenti: "headers already sent". Per questo sta in cima al file incluso per primo.
session_start();

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
return $_SESSION['utente'] ?? null; // salvato in sessione al login
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

// ---------- CSRF (Cross-Site Request Forgery) ----------
// Un codice casuale salvato in sessione e inserito in ogni form.
// Una pagina di un altro sito non lo conosce, quindi non può inviare il form al posto dell'utente.

//Cosa fa random_bytes(32): Genera 32 byte casuali. Un byte è un numero da 0 a 255, quindi sono 32 numeri a caso, ognuno tra 0 e 255. Il problema è che PHP restituisce questi byte come stringa, e molti di quei valori non corrispondono a caratteri stampabili. Quindi random_bytes da la casualità ma generando una stringa può inserire caratteri non utilizzabili nell'html

//Ecco perchè ci serve bin2hex(...) che trasforma ogni byte in due caratteri esadecimali, cioè cifre in base 16: 0-9 e a-f. Un byte va da 0 a 255, e in esadecimale da 00 a ff: servono sempre esattamente due caratteri. Questo metodo non aggiunge e non toglie casualità rende solo leggibili i 32 bytes. Da 32 bytes si ottengono 64 caratteri leggibili

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
http_response_code(419); //419 non è un codice standard: è quello che usa Laravel per questo errore ("Page Expired")
        exit('Richiesta non valida (token CSRF mancante o sbagliato).');
}
}