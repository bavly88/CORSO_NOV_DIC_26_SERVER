<?php
// 03 - UNA PAGINA RISERVATA: senza login si viene mandati a login.php
require __DIR__ . '/funzioni.php';
$utente = richiediLogin();

$titoloPagina = 'Area riservata';
require __DIR__ . '/parti/testata.php';
?>

<h1>Area riservata</h1>
<p>Benvenuta/o, <?= e($utente['nome']) ?>. Solo chi ha fatto il login vede questa pagina.</p>

<?php require __DIR__ . '/parti/piede.php'; ?>
