<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: autori.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

if ($id === false || $id === null) {
    header('Location: autori.php');
    exit;
}

$pdo = db();
$pdo->beginTransaction();

try {
    // Rimuove eventuali collegamenti nella tabella ponte libri_autori
    $stmtLink = $pdo->prepare('DELETE FROM libri_autori WHERE autore_id = ?');
    $stmtLink->execute([(int) $id]);

    // Elimina l'autore
    $stmtAutore = $pdo->prepare('DELETE FROM autori WHERE id = ?');
    $stmtAutore->execute([(int) $id]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}

header('Location: autori.php');
exit;