<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

function redirect(string $percorso): never
{
    header('Location: ' . $percorso);
    exit;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfCampo(): string
{
    $token = e(csrfToken());
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function csrfVerifica(): void
{
    $tokenSessione = $_SESSION['csrf_token'] ?? '';
    $tokenPost = $_POST['csrf_token'] ?? '';

    if (
        !is_string($tokenSessione) ||
        !is_string($tokenPost) ||
        $tokenSessione === '' ||
        !hash_equals($tokenSessione, $tokenPost)
    ) {
        http_response_code(403);
        exit('Token CSRF non valido.');
    }
}

function richiediLogin(): void
{
    $utente = $_SESSION['utente'] ?? null;
    if (!is_array($utente) || empty($utente['id'])) {
        redirect('login.php');
    }
}