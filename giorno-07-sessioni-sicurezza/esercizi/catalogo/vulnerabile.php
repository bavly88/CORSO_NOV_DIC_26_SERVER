<?php
/*
 * Problemi di sicurezza in vulnerabile.php:
 * 1. Mancanza di autenticazione (manca richiediLogin()):
 *    - Attacco: chiunque può aprire la pagina o inviare un POST per rinominare un libro senza essere loggato.
 *    - Correzione: aggiungere richiediLogin() dopo db.php.
 * 2. Assenza di protezione CSRF (mancano csrfVerifica() e csrfCampo()):
 *    - Attacco: un sito esterno può far inviare un POST a un utente autenticato cambiando il titolo a sua insaputa.
 *    - Correzione: chiamare csrfVerifica() sul POST e inserire <?= csrfCampo() ?> nel form.
 * 3. SQL Injection (db()->query() con valori utente non preparati):
 *    - Attacco: manipolare la query SELECT o UPDATE tramite $_GET['id'], $_POST['id'] o $_POST['titolo'].
 *    - Correzione: validare/castare l'ID a intero e usare prepared statements (prepare + execute).
 * 4. XSS (output non escapato con <?= $libro['titolo'] ?>):
 *    - Attacco: eseguire script o iniettare attributi HTML se il titolo contiene caratteri speciali.
 *    - Correzione: usare e(...) nella stampa HTML.
 */

require __DIR__ . '/db.php';
richiediLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();

    $id = (int) ($_POST['id'] ?? 0);
    $titolo = trim((string) ($_POST['titolo'] ?? ''));

    if ($id > 0 && $titolo !== '') {
        $stmtUpdate = db()->prepare("UPDATE libri SET titolo = ? WHERE id = ?");
        $stmtUpdate->execute([$titolo, $id]);
    }

    header('Location: libro.php?id=' . $id);
    exit;
}

$idGet = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT id, titolo FROM libri WHERE id = ?");
$stmt->execute([$idGet]);
$libro = $stmt->fetch();

if ($libro === false) {
    http_response_code(404);
    exit('Libro non trovato.');
}
?>
<h1>Rinomina: <?= e((string) $libro['titolo']) ?></h1>
<form method="post">
    <?= csrfCampo() ?>
    <input type="hidden" name="id" value="<?= (int) $libro['id'] ?>">
    <input name="titolo" value="<?= e((string) $libro['titolo']) ?>">
    <button>Salva</button>
</form>