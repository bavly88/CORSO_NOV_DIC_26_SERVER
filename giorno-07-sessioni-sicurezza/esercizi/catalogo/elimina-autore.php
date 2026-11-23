<?php
declare(strict_types=1);

require __DIR__ . '/db.php';
richiediLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: autori.php');
    exit;
}

csrfVerifica();

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
    $stmtLink = $pdo->prepare('DELETE FROM libri_autori WHERE autore_id = ?');
    $stmtLink->execute([(int) $id]);

    $stmtAutore = $pdo->prepare('DELETE FROM autori WHERE id = ?');
    $stmtAutore->execute([(int) $id]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}

$_SESSION['flash'] = 'Autore eliminato.';
header('Location: autori.php');
exit;