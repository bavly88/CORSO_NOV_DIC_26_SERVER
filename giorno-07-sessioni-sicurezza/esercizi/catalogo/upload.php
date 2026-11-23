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

$stmtLibro = db()->prepare('SELECT id, copertina FROM libri WHERE id = ?');
$stmtLibro->execute([$id]);
$libro = $stmtLibro->fetch();

if ($libro === false) {
    http_response_code(404);
    $titoloPagina = 'Libro non trovato';
    require __DIR__ . '/parti/testata.php';
    ?>
    <h1>404 - Libro non trovato</h1>
    <p><a href="index.php" class="btn-secondario">← Catalogo</a></p>
    <?php
    require __DIR__ . '/parti/piede.php';
    exit;
}

$cartellaUploads = __DIR__ . '/uploads';

if (($_POST['azione'] ?? '') === 'rimuovi_copertina') {
    $vecchiaCopertina = trim((string) ($libro['copertina'] ?? ''));

    if ($vecchiaCopertina !== '') {
        $baseName = basename($vecchiaCopertina);
        $baseReal = realpath($cartellaUploads);
        $percorsoFile = $baseReal !== false ? realpath($baseReal . '/' . $baseName) : false;

        if (
            $baseName === $vecchiaCopertina
            && $baseReal !== false
            && $percorsoFile !== false
            && str_starts_with($percorsoFile, $baseReal . DIRECTORY_SEPARATOR)
            && is_file($percorsoFile)
        ) {
            @unlink($percorsoFile);
        }
    }

    $stmtRimuovi = db()->prepare('UPDATE libri SET copertina = NULL WHERE id = ?');
    $stmtRimuovi->execute([$id]);

    $_SESSION['flash'] = 'Copertina rimossa.';
    header("Location: libro.php?id={$id}");
    exit;
}

$erroreUpload = '';
$maxBytes = 2 * 1024 * 1024; // 2 MB
$mimeConsentiti = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

if (!isset($_FILES['copertina']) || !is_array($_FILES['copertina'])) {
    $erroreUpload = 'Nessun file selezionato.';
} else {
    $file = $_FILES['copertina'];
    $code = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

    if ($code !== UPLOAD_ERR_OK) {
        $erroreUpload = match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Il file supera la dimensione massima consentita.',
            UPLOAD_ERR_NO_FILE => 'Seleziona un file immagine prima di inviare.',
            default => 'Errore durante il caricamento del file.',
        };
    } elseif (!is_uploaded_file((string) $file['tmp_name'])) {
        $erroreUpload = 'File caricato non valido.';
    } elseif ((int) $file['size'] <= 0 || (int) $file['size'] > $maxBytes) {
        $erroreUpload = 'La copertina deve pesare al massimo 2 MB.';
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeReale = $finfo !== false ? finfo_file($finfo, (string) $file['tmp_name']) : false;
        if ($finfo !== false) {
            finfo_close($finfo);
        }

        if (!is_string($mimeReale) || !isset($mimeConsentiti[$mimeReale])) {
            $erroreUpload = 'Formato non valido: sono ammesse solo immagini JPEG, PNG o WebP.';
        } else {
            $estensione = $mimeConsentiti[$mimeReale];
            $nomeFile = 'copertina_' . bin2hex(random_bytes(16)) . '.' . $estensione;

            if (!is_dir($cartellaUploads) && !mkdir($cartellaUploads, 0775, true) && !is_dir($cartellaUploads)) {
                $erroreUpload = 'Impossibile creare la cartella uploads.';
            } else {
                $destinazione = $cartellaUploads . '/' . $nomeFile;

                if (!move_uploaded_file((string) $file['tmp_name'], $destinazione)) {
                    $erroreUpload = 'Impossibile salvare il file nella cartella uploads.';
                } else {
                    $stmtUpdate = db()->prepare('UPDATE libri SET copertina = ? WHERE id = ?');
                    $stmtUpdate->execute([$nomeFile, $id]);

                    $vecchiaCopertina = (string) ($libro['copertina'] ?? '');
                    if ($vecchiaCopertina !== '' && $vecchiaCopertina !== $nomeFile) {
                        $vecchioPercorso = realpath($cartellaUploads . '/' . basename($vecchiaCopertina));
                        $baseReal = realpath($cartellaUploads);
                        if ($vecchioPercorso !== false && $baseReal !== false && str_starts_with($vecchioPercorso, $baseReal . DIRECTORY_SEPARATOR)) {
                            @unlink($vecchioPercorso);
                        }
                    }

                    $_SESSION['flash'] = 'Copertina aggiornata.';
                    header("Location: libro.php?id={$id}");
                    exit;
                }
            }
        }
    }
}

$titoloPagina = 'Caricamento copertina';
require __DIR__ . '/parti/testata.php';
?>

<div class="scheda-autore-top">
    <h1>Caricamento copertina</h1>
    <a href="libro.php?id=<?= $id ?>" class="btn-secondario">← Scheda libro</a>
</div>
<p class="errore"><?= e($erroreUpload) ?></p>

<?php require __DIR__ . '/parti/piede.php'; ?>