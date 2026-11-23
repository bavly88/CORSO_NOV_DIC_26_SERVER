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
    <?php if (utenteCorrente()) { ?>
        <a href="riservata.php">Area riservata</a>
        <a href="upload.php">Copertine</a>
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
