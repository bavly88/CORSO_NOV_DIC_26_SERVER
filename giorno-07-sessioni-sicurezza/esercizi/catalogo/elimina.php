<?php
declare(strict_types=1);

require __DIR__ . '/db.php';
richiediLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: index.php');
    exit;
}

csrfVerifica();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

if ($id === false || $id === null) {
    header('Location: index.php');
    exit;
}

$erroreEliminazione = '';

try {
    $stmt = db()->prepare('DELETE FROM libri WHERE id = ?');
    $stmt->execute([$id]);

    $_SESSION['flash'] = 'Libro eliminato.';
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    // In genere SQLSTATE[23000] / codice 1451 indica un vincolo di chiave esterna (es. prestiti attivi o storici)
    if ($e->getCode() === '23000') {
        $erroreEliminazione = 'Impossibile eliminare questo libro perché risulta collegato ad altri record (ad esempio dei prestiti).';
    } else {
        $erroreEliminazione = 'Si è verificato un errore durante l\'eliminazione del libro.';
    }
}

$titoloPagina = 'Eliminazione libro';
require __DIR__ . '/parti/testata.php';
?>

<h1>Impossibile eliminare il libro</h1>
<p><?= e($erroreEliminazione) ?></p>
<p>
    <a href="libro.php?id=<?= $id ?>">Torna al dettaglio del libro</a> |
    <a href="index.php">Torna al catalogo</a>
</p>

<?php require __DIR__ . '/parti/piede.php'; ?>