<?php
$titolo = "Il conte di Montecristo";
$autore = "Alexandre Dumas";
$anno = 1844;
$pagine = 1276;
$disponibile = true;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Scheda Libro: <?= $titolo ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        h1 { color: #333; }
        .info { margin-bottom: 10px; }
    </style>
</head>
<body>

    <h1><?= $titolo ?></h1>
    <div class="info">
        <p><strong>Autore:</strong> <?= $autore ?></p>
        <p><strong>Anno:</strong> <?= $anno ?></p>
        <p><strong>Pagine:</strong> <?= $pagine ?></p>
    </div>

    <p>lo Stato è : <strong><?= $disponibile ? "Disponibile" : "In prestito" ?></strong></p>

    <?php if ($pagine > 400) { ?>
        <p><em>Libro lungo</em></p>
    <?php } ?>

    <hr>

    <footer>
        <p><small>Pagina generata il <?= date('d/m/Y') ?> alle <?= date('H:i') ?></small></p>
    </footer>

</body>
</html>