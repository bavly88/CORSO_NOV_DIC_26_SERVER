<?php
/*
 * 04 - XSS (Cross-Site Scripting)
 * Un testo scritto da un utente viene mostrato a tutti gli altri.
 * Se si stampa senza htmlspecialchars, un utente può inserire HTML e JavaScript,
 * che verranno eseguiti nel browser di chi apre la pagina.
 *
 * Prova a inviare questo commento:
 *   <script>alert('Ho rubato la tua sessione')</script>
 * o quest'altro
 * <script>alert(document.cookie)</script>
 * poi guarda la pagina con ?sicuro=1 e senza.
 */
require __DIR__ . '/funzioni.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    db()->prepare("INSERT INTO commenti (testo) VALUES (?)")->execute([$_POST['testo'] ?? '']);
    redirect('xss.php');
}
if (isset($_GET['svuota'])) {
    db()->exec("DELETE FROM commenti");
    redirect('xss.php');
}

$commenti = db()->query("SELECT testo FROM commenti ORDER BY id DESC")->fetchAll();
$sicuro = isset($_GET['sicuro']);

$titoloPagina = 'XSS';
require __DIR__ . '/parti/testata.php';
?>

<h1>XSS - versione <?= $sicuro ? 'sicura (con e())' : 'VULNERABILE (senza e())' ?></h1>
<p><a href="xss.php">vulnerabile</a> | <a href="xss.php?sicuro=1">sicura</a> | <a href="xss.php?svuota=1">svuota</a></p>

<form method="post">
    <?= csrfCampo() ?>
    <label>Commento <textarea name="testo" rows="3"></textarea></label>
    <button type="submit">Invia</button>
</form>

<?php foreach ($commenti as $c) { ?>
    <?php if ($sicuro) { ?>
        <p><?= e($c['testo']) ?></p>
    <?php } else { ?>
        <p><?= $c['testo'] ?></p>   <!-- SBAGLIATO: stampato così com'è -->
    <?php } ?>
<?php } ?>

<?php require __DIR__ . '/parti/piede.php'; ?>
