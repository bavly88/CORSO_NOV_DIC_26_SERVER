<?php
// Il dettaglio di un libro: libro.php?id=8
require __DIR__ . '/db.php';

$id = (int) ($_GET['id'] ?? 0);

const DIMENSIONE_MAX = 2 * 1024 * 1024;
const TIPI_AMMESSI = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$erroreUpload = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();

    // 7.1 preferiti
    if (isset($_POST['preferito'])) {
        $_SESSION['preferiti'] ??= [];
        if (!in_array($id, $_SESSION['preferiti'])) {
            $_SESSION['preferiti'][] = $id;
        }
        redirect("libro.php?id=$id");
    }

    // 7.5 copertina (solo utenti loggati)
    if (isset($_POST['carica']) && utenteCorrente()) {
        $file = $_FILES['copertina'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $erroreUpload = 'Nessun file o errore nel caricamento.';
        } elseif ($file['size'] > DIMENSIONE_MAX) {
            $erroreUpload = 'Il file supera i 2 MB.';
        } else {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
            if (!isset(TIPI_AMMESSI[$mime])) {
                $erroreUpload = "Tipo non ammesso ($mime).";
            } else {
                $nome = bin2hex(random_bytes(16)) . '.' . TIPI_AMMESSI[$mime];
                move_uploaded_file($file['tmp_name'], __DIR__ . '/uploads/' . $nome);
                db()->prepare("UPDATE libri SET copertina = ? WHERE id = ?")->execute([$nome, $id]);
                $_SESSION['flash'] = 'Copertina salvata.';
                redirect("libro.php?id=$id");
            }
        }
    }
}

$stmt = db()->prepare(
    "SELECT l.*, g.nome AS genere
     FROM libri l LEFT JOIN generi g ON g.id = l.genere_id
     WHERE l.id = ?"
);
$stmt->execute([$id]);
$libro = $stmt->fetch();

if ($libro === false) {
    http_response_code(404);
    $titoloPagina = 'Libro non trovato';
    require __DIR__ . '/parti/testata.php';
    echo '<h1>Libro non trovato</h1>';
    require __DIR__ . '/parti/piede.php';
    exit;
}

// Una seconda query per gli autori (relazione N-N)
$stmt = db()->prepare(
    "SELECT a.id, a.nome, a.cognome
     FROM autori a JOIN libri_autori la ON la.autore_id = a.id
     WHERE la.libro_id = ?"
);
$stmt->execute([$id]);
$autori = $stmt->fetchAll();

$titoloPagina = $libro['titolo'];
require __DIR__ . '/parti/testata.php';
?>

<h1><?= e($libro['titolo']) ?></h1>
<?php if ($libro['copertina']) { ?>
    <img class="copertina" src="uploads/<?= e($libro['copertina']) ?>" alt="Copertina">
<?php } ?>
<p>
    <?php foreach ($autori as $a) { ?>
        <a href="autore.php?id=<?= $a['id'] ?>"><?= e($a['nome'] . ' ' . $a['cognome']) ?></a><br>
    <?php } ?>
</p>
<p>Anno: <?= $libro['anno'] ?? '-' ?> - Pagine: <?= $libro['pagine'] ?? '-' ?> - Genere: <?= e($libro['genere'] ?? '-') ?></p>
<form method="post">
    <?= csrfCampo() ?>
    <button type="submit" name="preferito" value="1">Aggiungi ai preferiti</button>
</form>

<?php if (utenteCorrente()) { ?>
    <p><a href="modifica.php?id=<?= $libro['id'] ?>">Modifica</a></p>
    <form method="post" action="elimina.php" onsubmit="return confirm('Eliminare il libro?')">
        <?= csrfCampo() ?>
        <input type="hidden" name="id" value="<?= $libro['id'] ?>">
        <button type="submit">Elimina</button>
    </form>
    <?php if (isset($_GET['errore'])) { ?>
        <p class="errore">Il libro non si può eliminare: ha dei prestiti registrati.</p>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <?= csrfCampo() ?>
        <label>Copertina (JPG, PNG, WEBP, al massimo 2 MB)
            <input type="file" name="copertina" accept="image/jpeg,image/png,image/webp">
        </label>
        <?php if ($erroreUpload) { ?><p class="errore"><?= e($erroreUpload) ?></p><?php } ?>
        <button type="submit" name="carica" value="1">Carica</button>
    </form>
<?php } ?>

<p><a href="index.php">Torna al catalogo</a></p>

<?php require __DIR__ . '/parti/piede.php'; ?>
