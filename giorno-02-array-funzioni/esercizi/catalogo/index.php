<?php
/*
 * 03 - IL CATALOGO: require, PHP dentro l'HTML, $_GET
 */
require __DIR__ . '/funzioni.php';
$libri = require __DIR__ . '/libri.php';   // libri.php restituisce l'array con return

$q = trim($_GET['q'] ?? '');
$genere = $_GET['genere'] ?? '';

// Filtro per genere (se presente)
if ($genere !== '') {
    $libri = array_filter($libri, fn($l) => $l['genere'] === $genere);
}

// Filtro per ricerca
if ($q !== '') {
    $libri = array_filter($libri, fn($l) =>
        str_contains(strtolower($l['titolo']), strtolower($q)) ||
        str_contains(strtolower($l['autore']), strtolower($q)));
}

$totaleLibri = count($libri);
$titoloPagina = "Catalogo";
if ($genere !== '') {
    $titoloPagina .= " - " . $genere;
}

require __DIR__ . '/parti/testata.php';

// Generi disponibili per i pulsanti
$generiDisponibili = ['Romanzo', 'Racconti', 'Giallo', 'Saggio'];
?>

<div class="page-header">
    <h1><?= e($titoloPagina) ?></h1>
    <span class="subtitle">&bull; <?= $totaleLibri ?> <?= $totaleLibri === 1 ? 'libro' : 'libri' ?></span>
</div>

<!-- Pulsanti dei filtri genere -->
<div class="filter-buttons">
    <?php if ($genere === ''): ?>
        <strong>Tutti</strong>
    <?php else: ?>
        <a href="index.php">Tutti</a>
    <?php endif; ?>

    <?php foreach ($generiDisponibili as $g): ?>
        <?php if ($genere === $g): ?>
            <strong><?= e($g) ?></strong>
        <?php else: ?>
            <a href="index.php?genere=<?= urlencode($g) ?>"><?= e($g) ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- form con method="get" -->
<form method="get">
    <!-- Se c'è un genere attivo, lo preserviamo nella ricerca -->
    <?php if ($genere !== ''): ?>
        <input type="hidden" name="genere" value="<?= e($genere) ?>">
    <?php endif; ?>
    
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Titolo o autore">
    <button type="submit">Cerca</button>
    
    <?php if ($q !== '' || $genere !== ''): ?>
        <a href="index.php" class="btn" style="background-color: #64748b; text-decoration: none; display: inline-flex; align-items: center;">Resetta</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>Titolo</th>
            <th>Autore</th>
            <th>Anno</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($libri as $libro) { ?>
            <tr class="<?= isset($libro['disponibile']) && !$libro['disponibile'] ? 'esaurito' : '' ?>">
                <!-- il link punta giustamente a libro.php con l'id -->
                <td><a href="libro.php?id=<?= $libro['id'] ?>"><?= e($libro['titolo']) ?></a></td>
                <td><?= e($libro['autore']) ?></td>
                <td><?= $libro['anno'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php if (count($libri) === 0) { ?>
    <p>Nessun libro trovato.</p>
<?php } ?>

<?php require __DIR__ . '/parti/piede.php'; ?>