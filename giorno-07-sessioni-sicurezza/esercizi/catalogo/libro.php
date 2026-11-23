<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$utente = $_SESSION['utente'] ?? null;
$isLoggedIn = is_array($utente) && !empty($utente['id']);
$utenteId = $isLoggedIn ? (int) $utente['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();

    if (($_POST['azione'] ?? '') === 'aggiungi_preferito') {
        if (!$isLoggedIn) {
            header('Location: login.php');
            exit;
        }

        $idDaAggiungere = (int) ($_POST['id'] ?? 0);

        if ($idDaAggiungere > 0) {
            $checkStmt = db()->prepare("SELECT id FROM libri WHERE id = ?");
            $checkStmt->execute([$idDaAggiungere]);

            if ($checkStmt->fetch() !== false) {
                $insStmt = db()->prepare(
                    "INSERT IGNORE INTO preferiti (utente_id, libro_id) VALUES (?, ?)"
                );
                $insStmt->execute([$utenteId, $idDaAggiungere]);
                $_SESSION['flash'] = 'Libro aggiunto ai preferiti.';
            }

            header('Location: libro.php?id=' . $idDaAggiungere);
            exit;
        }
    }
}

$id = (int) ($_GET['id'] ?? 0);

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
    ?>
    <div class="scheda-autore-top">
        <h1>404 - Libro non trovato</h1>
        <a href="index.php" class="btn-secondario">← Catalogo</a>
    </div>
    <p class="vuoto-box">Il libro richiesto non esiste o il link non è valido.</p>
    <?php
    require __DIR__ . '/parti/piede.php';
    exit;
}

$stmt = db()->prepare(
    "SELECT a.id, a.nome, a.cognome
     FROM autori a JOIN libri_autori la ON la.autore_id = a.id
     WHERE la.libro_id = ?"
);
$stmt->execute([$id]);
$autori = $stmt->fetchAll();

$giaPreferito = false;
if ($isLoggedIn) {
    $prefStmt = db()->prepare(
        "SELECT 1 FROM preferiti WHERE utente_id = ? AND libro_id = ? LIMIT 1"
    );
    $prefStmt->execute([$utenteId, (int) $libro['id']]);
    $giaPreferito = $prefStmt->fetchColumn() !== false;
}

$nomeCopertina = trim((string) ($libro['copertina'] ?? ''));
$fileCopertinaSicuro = '';
if ($nomeCopertina !== '') {
    $baseName = basename($nomeCopertina);
    if ($baseName === $nomeCopertina && is_file(__DIR__ . '/uploads/' . $baseName)) {
        $fileCopertinaSicuro = $baseName;
    }
}

$titoloPagina = (string) $libro['titolo'];
require __DIR__ . '/parti/testata.php';
?>

<div class="scheda-libro-header">
    <div class="scheda-libro-titolo-wrap">
        <h1><?= e((string) $libro['titolo']) ?></h1>

        <?php if (!empty($autori)): ?>
            <p class="scheda-libro-autori">
                <span>di</span>
                <?php
                $linkAutori = [];
                foreach ($autori as $a) {
                    $nomeAut = trim((string) ($a['nome'] ?? '') . ' ' . (string) ($a['cognome'] ?? ''));
                    $linkAutori[] = '<a href="autore.php?id=' . (int) $a['id'] . '">' . e($nomeAut) . '</a>';
                }
                echo implode(', ', $linkAutori);
                ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="azioni-top">
        <?php if ($isLoggedIn): ?>
            <form method="post" action="libro.php?id=<?= (int) $libro['id'] ?>" class="form-inline">
                <?= csrfCampo() ?>
                <input type="hidden" name="azione" value="aggiungi_preferito">
                <input type="hidden" name="id" value="<?= (int) $libro['id'] ?>">
                <button type="submit" class="btn-secondario" <?= $giaPreferito ? 'disabled' : '' ?>>
                    <?= $giaPreferito ? 'Nei preferiti' : 'Aggiungi ai preferiti' ?>
                </button>
            </form>

            <a href="modifica.php?id=<?= (int) $libro['id'] ?>" class="btn-secondario">Modifica</a>
        <?php endif; ?>

        <a href="index.php" class="btn-secondario">← Catalogo</a>
    </div>
</div>

<div class="scheda-libro-corpo">
    <?php if ($fileCopertinaSicuro !== ''): ?>
        <div class="copertina-libro">
            <img src="uploads/<?= e($fileCopertinaSicuro) ?>" alt="Copertina di <?= e((string) $libro['titolo']) ?>">
        </div>
    <?php endif; ?>

    <dl class="scheda-anagrafica">
        <dt>Genere</dt>
        <dd><?= e(!empty($libro['genere']) ? (string) $libro['genere'] : 'Non indicato') ?></dd>

        <dt>Anno di pubblicazione</dt>
        <dd><?= e(!empty($libro['anno']) ? (string) $libro['anno'] : 'Non indicato') ?></dd>

        <dt>Numero di pagine</dt>
        <dd><?= e(!empty($libro['pagine']) ? (int) $libro['pagine'] . ' pagine' : 'Non indicato') ?></dd>
    </dl>
</div>

<?php if ($isLoggedIn): ?>
    <section class="blocco-moderno">
        <h2>Copertina</h2>
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <?= csrfCampo() ?>
            <input type="hidden" name="id" value="<?= (int) $libro['id'] ?>">
            <div class="campo-gruppo">
                <label for="copertina">Carica immagine (JPEG, PNG, WebP - max 2 MB)</label>
                <input type="file" id="copertina" name="copertina" accept="image/jpeg,image/png,image/webp" required>
            </div>
            <button type="submit" class="btn-secondario">Carica copertina</button>
        </form>

        <?php if ($fileCopertinaSicuro !== ''): ?>
            <form method="post" action="upload.php" class="form-inline" onsubmit="return confirm('Rimuovere la copertina?')">
                <?= csrfCampo() ?>
                <input type="hidden" name="id" value="<?= (int) $libro['id'] ?>">
                <input type="hidden" name="azione" value="rimuovi_copertina">
                <button type="submit" class="btn-secondario">Rimuovi copertina</button>
            </form>
        <?php endif; ?>
    </section>

    <div class="zona-elimina">
        <form method="post" action="elimina.php" onsubmit="return confirm('Eliminare il libro?')">
            <?= csrfCampo() ?>
            <input type="hidden" name="id" value="<?= (int) $libro['id'] ?>">
            <button type="submit">Elimina</button>
        </form>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/parti/piede.php'; ?>