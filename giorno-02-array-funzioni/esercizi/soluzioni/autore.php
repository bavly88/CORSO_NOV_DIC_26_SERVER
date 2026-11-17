<?php
require __DIR__ . '/funzioni.php';
$libri = require __DIR__ . '/libri.php';
$nome = $_GET['nome'] ?? '';
$suoiLibri = array_filter($libri, fn($l) => $l['autore'] === $nome);
if (count($suoiLibri) === 0) {
    http_response_code(404);
    $titoloPagina = 'Autore non trovato';
    require __DIR__ . '/parti/testata.php';
    echo '<h1>Autore non trovato: ' . e($nome) . '</h1><p><a href="autori.php">Torna agli autori</a></p>';
    require __DIR__ . '/parti/piede.php';
    exit;
}
$titoloPagina = $nome;
require __DIR__ . '/parti/testata.php';
?>
<h1><?= e($nome) ?></h1>
<ul>
<?php foreach ($suoiLibri as $libro) { ?>
    <li><a href="libro.php?id=<?= $libro['id'] ?>"><?= e($libro['titolo']) ?></a> (<?= $libro['anno'] ?>)</li>
<?php } ?>
</ul>
<?php require __DIR__ . '/parti/piede.php'; ?>
