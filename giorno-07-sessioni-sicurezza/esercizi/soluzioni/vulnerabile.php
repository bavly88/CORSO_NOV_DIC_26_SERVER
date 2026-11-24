<?php
/*
 * Esercizio 7.6 - Soluzione
 * Problemi della versione originale:
 * 1. SQL injection: $titolo, $id e $_GET['id'] erano incollati nelle query.
 *    Con un titolo come  x' WHERE 1=1 --  si rinominavano tutti i libri.
 *    -> prepare ed execute con i segnaposto.
 * 2. XSS: il titolo veniva stampato senza e(). Un titolo con <script> veniva eseguito.
 *    -> e() in ogni echo di dati.
 * 3. CSRF: il form POST non aveva il token: un altro sito poteva inviarlo.
 *    -> csrfCampo() nel form, csrfVerifica() all'arrivo.
 * 4. (In più) Nessun controllo di login e nessuna verifica che il libro esista.
 */
require __DIR__ . '/db.php';
richiediLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    $id = (int) ($_POST['id'] ?? 0);
    $titolo = trim($_POST['titolo'] ?? '');
    if ($titolo !== '') {
        db()->prepare("UPDATE libri SET titolo = ? WHERE id = ?")->execute([$titolo, $id]);
    }
    redirect('libro.php?id=' . $id);
}

$stmt = db()->prepare("SELECT * FROM libri WHERE id = ?");
$stmt->execute([(int) ($_GET['id'] ?? 0)]);
$libro = $stmt->fetch();
if (!$libro) {
    http_response_code(404);
    exit('Libro non trovato');
}
?>
<h1>Rinomina: <?= e($libro['titolo']) ?></h1>
<form method="post">
    <?= csrfCampo() ?>
    <input type="hidden" name="id" value="<?= $libro['id'] ?>">
    <input name="titolo" value="<?= e($libro['titolo']) ?>">
    <button>Salva</button>
</form>
