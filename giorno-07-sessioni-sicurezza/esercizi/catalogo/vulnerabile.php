<?php
// Esercizio 7.6 - Questa pagina ha almeno tre problemi di sicurezza. Trovali.
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titolo = $_POST['titolo'];
    db()->query("UPDATE libri SET titolo = '$titolo' WHERE id = $id");
    header('Location: libro.php?id=' . $id);
    exit;
}

$libro = db()->query("SELECT * FROM libri WHERE id = " . $_GET['id'])->fetch();
?>
<h1>Rinomina: <?= $libro['titolo'] ?></h1>
<form method="post">
    <input type="hidden" name="id" value="<?= $libro['id'] ?>">
    <input name="titolo" value="<?= $libro['titolo'] ?>">
    <button>Salva</button>
</form>
