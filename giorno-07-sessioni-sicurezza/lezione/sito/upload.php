<?php
/*
 * 05 - UPLOAD DI UNA COPERTINA
 * I file vengono salvati nella cartella uploads/, il nome del file nella colonna libri.copertina.
 *
 * Quattro regole:
 *  1. controllare l'esito del caricamento;
 *  2. controllare la dimensione;
 *  3. controllare il TIPO dal CONTENUTO del file (finfo), non dal nome;
 *  4. dare al file un nome nuovo, casuale: mai usare quello scelto dall'utente.
 */
require __DIR__ . '/funzioni.php';
richiediLogin();

const DIMENSIONE_MAX = 2 * 1024 * 1024;   // 2 MB
const TIPI_AMMESSI = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

$errore = null;
$libri = db()->query("SELECT id, titolo, copertina FROM libri ORDER BY titolo")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    $file = $_FILES['copertina'] ?? null;   // name, type, tmp_name, size, error
    $libroId = (int) ($_POST['libro_id'] ?? 0);

    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $errore = 'Nessun file scelto.';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {                       // regola 1
        $errore = 'Errore durante il caricamento.';
    } elseif ($file['size'] > DIMENSIONE_MAX) {                         // regola 2
        $errore = 'Il file supera i 2 MB.';
    } else {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']); // regola 3
        if (!isset(TIPI_AMMESSI[$mime])) {
            $errore = "Tipo non ammesso ($mime): solo JPG, PNG, WEBP.";
        } else {
            $nome = bin2hex(random_bytes(16)) . '.' . TIPI_AMMESSI[$mime]; // regola 4
            // il file caricato sta in una cartella temporanea: va spostato
            move_uploaded_file($file['tmp_name'], __DIR__ . '/uploads/' . $nome);
            db()->prepare("UPDATE libri SET copertina = ? WHERE id = ?")->execute([$nome, $libroId]);
            redirect('upload.php');
        }
    }
}

$titoloPagina = 'Copertine';
require __DIR__ . '/parti/testata.php';
?>

<h1>Copertine</h1>
<!-- enctype="multipart/form-data" è obbligatorio: senza, il file non arriva -->
<form method="post" enctype="multipart/form-data">
    <?= csrfCampo() ?>
    <label>Libro
        <select name="libro_id">
            <?php foreach ($libri as $l) { ?>
                <option value="<?= $l['id'] ?>"><?= e($l['titolo']) ?></option>
            <?php } ?>
        </select>
    </label>
    <label>Copertina (JPG, PNG, WEBP, al massimo 2 MB)
        <input type="file" name="copertina" accept="image/jpeg,image/png,image/webp">
    </label>
    <?php if ($errore) { ?><p class="errore"><?= e($errore) ?></p><?php } ?>
    <button type="submit">Carica</button>
</form>

<?php foreach ($libri as $l) { ?>
    <?php if ($l['copertina']) { ?>
        <span><?= e($l['titolo']) ?></span>
        <img class="copertina" src="uploads/<?= e($l['copertina']) ?>" alt="<?= e($l['titolo']) ?>">
        <br>
    <?php } ?>
<?php } ?>

<?php require __DIR__ . '/parti/piede.php'; ?>