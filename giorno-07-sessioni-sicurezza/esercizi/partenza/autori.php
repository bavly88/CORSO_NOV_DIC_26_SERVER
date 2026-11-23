<?php
// Esercizio 6.1 - La pagina degli autori
require __DIR__ . '/db.php';

$autori = db()->query(
    "SELECT a.id, a.nome, a.cognome, COUNT(la.libro_id) AS libri
     FROM autori a
     LEFT JOIN libri_autori la ON la.autore_id = a.id
     GROUP BY a.id, a.nome, a.cognome
     ORDER BY a.cognome"
)->fetchAll();

$titoloPagina = 'Autori';
require __DIR__ . '/parti/testata.php';
?>

<h1>Autori</h1>
<ul>
    <?php foreach ($autori as $a) { ?>
        <li><a href="autore.php?id=<?= $a['id'] ?>"><?= e($a['cognome'] . ' ' . $a['nome']) ?></a> (<?= $a['libri'] ?>)</li>
    <?php } ?>
</ul>

<?php require __DIR__ . '/parti/piede.php'; ?>
