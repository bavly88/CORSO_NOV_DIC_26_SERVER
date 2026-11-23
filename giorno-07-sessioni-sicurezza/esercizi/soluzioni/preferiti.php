<?php
// Esercizio 7.1 - I preferiti in sessione
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    unset($_SESSION['preferiti']);
    redirect('preferiti.php');
}

$libri = [];
$stmt = db()->prepare("SELECT id, titolo FROM libri WHERE id = ?");
foreach ($_SESSION['preferiti'] ?? [] as $id) {
    $stmt->execute([$id]);
    $libro = $stmt->fetch();
    if ($libro) {
        $libri[] = $libro;
    }
}

$titoloPagina = 'Preferiti';
require __DIR__ . '/parti/testata.php';
?>

<h1>Preferiti</h1>
<?php if (!$libri) { ?><p>Nessun preferito.</p><?php } ?>
<ul>
    <?php foreach ($libri as $l) { ?>
        <li><a href="libro.php?id=<?= $l['id'] ?>"><?= e($l['titolo']) ?></a></li>
    <?php } ?>
</ul>
<form method="post">
    <?= csrfCampo() ?>
    <button type="submit">Svuota</button>
</form>

<?php require __DIR__ . '/parti/piede.php'; ?>
