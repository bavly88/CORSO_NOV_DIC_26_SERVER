<?php
declare(strict_types=1);

require __DIR__ . '/db.php';
richiediLogin();

$utente = $_SESSION['utente'] ?? null;
$utenteId = (is_array($utente) && !empty($utente['id'])) ? (int) $utente['id'] : 0;

if ($utenteId <= 0) {
    header('Location: login.php');
    exit;
}

// Svuota i preferiti via POST (PRG)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();

    if (($_POST['azione'] ?? '') === 'svuota_preferiti') {
        $delStmt = db()->prepare('DELETE FROM preferiti WHERE utente_id = ?');
        $delStmt->execute([$utenteId]);

        $_SESSION['flash'] = 'Preferiti svuotati.';
        header('Location: preferiti.php');
        exit;
    }
}

$stmt = db()->prepare(
    'SELECT l.id, l.titolo
     FROM preferiti p
     INNER JOIN libri l ON l.id = p.libro_id
     WHERE p.utente_id = ?
     ORDER BY l.titolo ASC'
);
$stmt->execute([$utenteId]);
$titoliPreferiti = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titoloPagina = 'Preferiti';
require __DIR__ . '/parti/testata.php';
?>

<h1>I tuoi libri preferiti</h1>

<?php if (empty($titoliPreferiti)): ?>
    <p>Non hai ancora aggiunto nessun libro ai preferiti.</p>
<?php else: ?>
    <ul>
        <?php foreach ($titoliPreferiti as $libro): ?>
            <li>
                <a href="libro.php?id=<?= (int) $libro['id'] ?>">
                    <?= e((string) $libro['titolo']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="post" action="preferiti.php">
        <?= csrfCampo() ?>
        <input type="hidden" name="azione" value="svuota_preferiti">
        <button type="submit" class="btn-secondario">Svuota</button>
    </form>
<?php endif; ?>

<?php require __DIR__ . '/parti/piede.php'; ?>