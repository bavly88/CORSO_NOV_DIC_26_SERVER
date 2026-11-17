<?php
require __DIR__ . '/funzioni.php';
$libri = require __DIR__ . '/libri.php';
$conteggi = array_count_values(array_column($libri, 'autore'));
ksort($conteggi);
$titoloPagina = 'Autori';
require __DIR__ . '/parti/testata.php';
?>
<h1>Autori</h1>
<ul>
<?php foreach ($conteggi as $autore => $n) { ?>
    <li><a href="autore.php?nome=<?= urlencode($autore) ?>"><?= e($autore) ?></a> (<?= $n ?> <?= $n === 1 ? 'libro' : 'libri' ?>)</li>
<?php } ?>
</ul>
<?php require __DIR__ . '/parti/piede.php'; ?>
