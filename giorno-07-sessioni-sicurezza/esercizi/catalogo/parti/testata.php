<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$utente = $_SESSION['utente'] ?? null;
$isLoggedIn = is_array($utente) && !empty($utente['id']);
$nomeUtente = $isLoggedIn ? (string) ($utente['nome'] ?? 'Utente') : '';

$numeroPreferiti = 0;
if ($isLoggedIn) {
    try {
        if (function_exists('db')) {
            $stmtPrefCount = db()->prepare('SELECT COUNT(*) FROM preferiti WHERE utente_id = ?');
            $stmtPrefCount->execute([(int) $utente['id']]);
            $numeroPreferiti = (int) $stmtPrefCount->fetchColumn();
        }
    } catch (Throwable $e) {
        $numeroPreferiti = 0;
    }
}

$messaggioFlash = $_SESSION['flash'] ?? null;
if (is_string($messaggioFlash) && $messaggioFlash !== '') {
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titoloPagina) ?> - Bookshelf</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
<header>
    <a href="index.php"><strong>Bookshelf</strong></a>
    <a href="index.php">Catalogo</a>
    <a href="autori.php">Autori</a>

    <?php if ($isLoggedIn): ?>
        <a href="preferiti.php">Preferiti (<?= $numeroPreferiti ?>)</a>
        <a href="nuovo.php">Nuovo libro</a>
        <a href="nuovo-autore.php">Nuovo autore</a>
        <span class="utente-nome">Ciao, <?= e($nomeUtente) ?></span>
        <a href="logout.php">Esci</a>
    <?php else: ?>
        <a href="login.php">Accedi</a>
        <a href="registrazione.php">Registrati</a>
    <?php endif; ?>
</header>
<main>
<?php if (is_string($messaggioFlash) && $messaggioFlash !== ''): ?>
    <p class="flash"><?= e($messaggioFlash) ?></p>
<?php endif; ?>