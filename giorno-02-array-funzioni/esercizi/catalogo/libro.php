<?php
/*
- 03 - IL DETTAGLIO DI UN LIBRO: libro.php?id=8
 */
require __DIR__ . '/funzioni.php';
$libri = require __DIR__ . '/libri.php';

// (int) converte in numero: "8" -> 8, "abc" -> 0
$id = (int) ($_GET['id'] ?? 0);
$libro = trovaLibro($libri, $id);

if ($libro === null) {
    http_response_code(404);   // il codice HTTP "non trovato"
    $titoloPagina = 'Libro non trovato';
    require __DIR__ . '/parti/testata.php';
    ?>
    <div class="page-header">
        <h1>Libro non trovato</h1>
    </div>
    <p>Spiacenti, il libro cercato non è presente nel catalogo.</p>
    <p style="margin-top: 28px;">
        <a href="index.php" class="btn" style="text-decoration: none; display: inline-block;">&larr; Torna al catalogo</a>
    </p>
    <?php
    require __DIR__ . '/parti/piede.php';
    exit;   // ferma lo script
}

$titoloPagina = $libro['titolo'];
require __DIR__ . '/parti/testata.php';
?>

<div class="page-header" style="justify-content: space-between; align-items: center;">
    <h1><?= e($libro['titolo']) ?></h1>
    <?php if ($libro['disponibile']): ?>
        <span class="status-badge available">Disponibile</span>
    <?php else: ?>
        <span class="status-badge unavailable">In prestito</span>
    <?php endif; ?>
</div>

<div class="book-details-grid">
    <div class="detail-box">
        <span class="detail-label">Autore</span>
        <a href="autore.php?nome=<?= urlencode($libro['autore']) ?>" class="detail-value link"><?= e($libro['autore']) ?></a>
    </div>
    <div class="detail-box">
        <span class="detail-label">Anno</span>
        <span class="detail-value"><?= e((string)$libro['anno']) ?></span>
    </div>
    <div class="detail-box">
        <span class="detail-label">Genere</span>
        <span class="detail-value"><?= e($libro['genere']) ?></span>
    </div>
    <div class="detail-box">
        <span class="detail-label">Pagine</span>
        <span class="detail-value"><?= !empty($libro['pagine']) ? e((string)$libro['pagine']) : '—' ?></span>
    </div>
</div>

<p style="margin-top: 32px;">
    <a href="index.php" class="btn" style="text-decoration: none; display: inline-block;">&larr; Torna al catalogo</a>
</p>

<?php require __DIR__ . '/parti/piede.php'; ?>