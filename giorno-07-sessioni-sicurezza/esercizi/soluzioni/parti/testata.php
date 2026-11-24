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
    <a href="preferiti.php">Preferiti (<?= count($_SESSION['preferiti'] ?? []) ?>)</a>
    <?php if (utenteCorrente()) { ?>
        <a href="nuovo.php">Nuovo libro</a>
        <a href="nuovo-autore.php">Nuovo autore</a>
        <span>Ciao, <?= e(utenteCorrente()['nome']) ?></span>
        <form method="post" action="logout.php" class="in-linea">
            <?= csrfCampo() ?>
            <button type="submit">Esci</button>
        </form>
    <?php } else { ?>
        <a href="login.php">Accedi</a>
        <a href="registrazione.php">Registrati</a>
    <?php } ?>
</header>
<main>
<?php if (isset($_SESSION['flash'])) { ?>
    <p class="flash"><?= e($_SESSION['flash']) ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php } ?>
