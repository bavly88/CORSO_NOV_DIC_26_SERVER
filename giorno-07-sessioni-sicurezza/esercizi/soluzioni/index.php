<?php
/*
 * 03 - IL CATALOGO DAL DATABASE
 * Esecuzione: dalla cartella 03-catalogo:  php -S localhost:8000
 * È il catalogo del giorno 2: al posto dell'array di libri.php c'è il database.
 */
require __DIR__ . '/db.php';

$q = trim($_GET['q'] ?? '');

$sql = "SELECT l.id, l.titolo, l.anno, g.nome AS genere
        FROM libri l
        LEFT JOIN generi g ON g.id = l.genere_id";
$valori = [];

if ($q !== '') {
    $sql .= " WHERE l.titolo LIKE ?";
    $valori[] = '%' . $q . '%';
}
$sql .= " ORDER BY l.titolo";

$stmt = db()->prepare($sql);
$stmt->execute($valori);
$libri = $stmt->fetchAll();

$titoloPagina = 'Catalogo';
require __DIR__ . '/parti/testata.php';
?>

<h1>Catalogo (<?= count($libri) ?> libri)</h1>

<form method="get">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Titolo">
    <button type="submit">Cerca</button>
</form>

<table>
    <tr><th>Titolo</th><th>Genere</th><th>Anno</th></tr>
    <?php foreach ($libri as $libro) { ?>
        <tr>
            <td><a href="libro.php?id=<?= $libro['id'] ?>"><?= e($libro['titolo']) ?></a></td>
            <td><?= e($libro['genere']) ?></td>
            <td><?= $libro['anno'] ?></td>
        </tr>
    <?php } ?>
</table>

<?php require __DIR__ . '/parti/piede.php'; ?>
