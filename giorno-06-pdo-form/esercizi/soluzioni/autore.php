<?php
// Esercizio 6.2 - La pagina di un autore
require __DIR__ . '/db.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM autori WHERE id = ?");
$stmt->execute([$id]);
$autore = $stmt->fetch();

if ($autore === false) {
    http_response_code(404);
    $titoloPagina = 'Autore non trovato';
    require __DIR__ . '/parti/testata.php';
    echo '<h1>Autore non trovato</h1>';
    require __DIR__ . '/parti/piede.php';
    exit;
}

$stmt = db()->prepare(
    "SELECT l.id, l.titolo, l.anno
     FROM libri l JOIN libri_autori la ON la.libro_id = l.id
     WHERE la.autore_id = ?
     ORDER BY l.anno"
);
$stmt->execute([$id]);
$libri = $stmt->fetchAll();

$titoloPagina = $autore['nome'] . ' ' . $autore['cognome'];
require __DIR__ . '/parti/testata.php';
?>

<h1><?= e($autore['nome'] . ' ' . $autore['cognome']) ?></h1>
<p>Nazionalità: <?= e($autore['nazionalita'] ?? '-') ?> - Nato nel <?= $autore['anno_nascita'] ?? '-' ?></p>

<h2>Libri</h2>
<?php if (count($libri) === 0) { ?>
    <p>Nessun libro in catalogo.</p>
<?php } ?>
<ul>
    <?php foreach ($libri as $l) { ?>
        <li><a href="libro.php?id=<?= $l['id'] ?>"><?= e($l['titolo']) ?></a> (<?= $l['anno'] ?>)</li>
    <?php } ?>
</ul>

<?php require __DIR__ . '/parti/piede.php'; ?>
