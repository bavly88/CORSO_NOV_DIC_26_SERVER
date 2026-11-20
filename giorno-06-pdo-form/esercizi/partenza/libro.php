<?php
// Il dettaglio di un libro: libro.php?id=8
require __DIR__ . '/db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare(
    "SELECT l.*, g.nome AS genere
     FROM libri l LEFT JOIN generi g ON g.id = l.genere_id
     WHERE l.id = ?"
);
$stmt->execute([$id]);
$libro = $stmt->fetch();

if ($libro === false) {
    http_response_code(404);
    $titoloPagina = 'Libro non trovato';
    require __DIR__ . '/parti/testata.php';
    echo '<h1>Libro non trovato</h1>';
    require __DIR__ . '/parti/piede.php';
    exit;
}

// Una seconda query per gli autori (relazione N-N)
$stmt = db()->prepare(
    "SELECT a.nome, a.cognome
     FROM autori a JOIN libri_autori la ON la.autore_id = a.id
     WHERE la.libro_id = ?"
);
$stmt->execute([$id]);
$autori = $stmt->fetchAll();

$titoloPagina = $libro['titolo'];
require __DIR__ . '/parti/testata.php';
?>

<h1><?= e($libro['titolo']) ?></h1>
<p>
    <?php foreach ($autori as $a) { ?>
        <?= e($a['nome'] . ' ' . $a['cognome']) ?><br>
    <?php } ?>
</p>
<p>Anno: <?= $libro['anno'] ?? '-' ?> - Pagine: <?= $libro['pagine'] ?? '-' ?> - Genere: <?= e($libro['genere'] ?? '-') ?></p>
<p><a href="index.php">Torna al catalogo</a></p>

<?php require __DIR__ . '/parti/piede.php'; ?>
