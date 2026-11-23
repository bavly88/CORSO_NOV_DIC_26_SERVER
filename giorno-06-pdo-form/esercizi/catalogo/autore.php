<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$idAutore = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$autore = null;

if ($idAutore !== false && $idAutore !== null) {
    $stmtAutore = db()->prepare(
        'SELECT id, nome, cognome, nazionalita, anno_nascita
         FROM autori
         WHERE id = :id'
    );
    $stmtAutore->execute(['id' => $idAutore]);
    $autore = $stmtAutore->fetch() ?: null;
}

if ($autore === null) {
    http_response_code(404);
    $titoloPagina = 'Autore non trovato';
    require_once __DIR__ . '/parti/testata.php';
    ?>
    <div class="scheda-autore-top">
        <h1>404 - Autore non trovato</h1>
        <a href="autori.php" class="btn-secondario">← Elenco autori</a>
    </div>
    <p class="vuoto-box">L'autore richiesto non esiste o il link non è valido.</p>
    </main>
    <?php
    require_once __DIR__ . '/parti/piede.php';
    exit;
}

$stmtLibri = db()->prepare(
    'SELECT l.id, l.titolo, l.anno
     FROM libri l
     INNER JOIN libri_autori la ON l.id = la.libro_id
     WHERE la.autore_id = :autore_id
     ORDER BY l.titolo ASC'
);
$stmtLibri->execute(['autore_id' => (int) $autore['id']]);
$libri = $stmtLibri->fetchAll();

$nomeCompleto = trim((string) $autore['nome'] . ' ' . (string) $autore['cognome']);
$titoloPagina = $nomeCompleto !== '' ? $nomeCompleto : 'Dettaglio autore';

$messaggioConfermaEliminazione = !empty($libri)
    ? 'Questo autore ha ' . count($libri) . ' ' . (count($libri) === 1 ? 'libro collegato' : 'libri collegati') . '. Eliminare comunque l\'autore?'
    : 'Eliminare questo autore?';

require_once __DIR__ . '/parti/testata.php';
?>

<div class="scheda-autore-top">
    <h1><?= e($nomeCompleto !== '' ? $nomeCompleto : 'Dettaglio autore') ?></h1>
    <a href="autori.php" class="btn-secondario">← Elenco autori</a>
</div>

<dl class="scheda-anagrafica">
    <dt>Nome</dt>
    <dd><?= e((string) $autore['nome']) ?></dd>

    <dt>Cognome</dt>
    <dd><?= e((string) $autore['cognome']) ?></dd>

    <dt>Nazionalità</dt>
    <dd><?= e(isset($autore['nazionalita']) && $autore['nazionalita'] !== '' ? (string) $autore['nazionalita'] : 'Non indicata') ?></dd>

    <dt>Anno di nascita</dt>
    <dd><?= e(isset($autore['anno_nascita']) && $autore['anno_nascita'] !== null ? (string) $autore['anno_nascita'] : 'Non indicato') ?></dd>
</dl>

<section class="sezione-libri-autore">
    <h2>Libri dell'autore</h2>

    <?php if (empty($libri)): ?>
        <p class="vuoto-box">Nessun libro associato a questo autore.</p>
    <?php else: ?>
        <ul class="lista-libri-autore">
            <?php foreach ($libri as $libro): ?>
                <?php
                    $idLibro = (int) $libro['id'];
                    $titoloLibro = (string) $libro['titolo'];
                    $annoLibro = $libro['anno'] ?? null;
                ?>
                <li>
                    <a href="libro.php?id=<?= $idLibro ?>" class="libro-link"><?= e($titoloLibro) ?></a>
                    <?php if (!empty($annoLibro)): ?>
                        <span class="libro-anno"><?= e((string) $annoLibro) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<div class="zona-elimina">
    <form method="post" action="elimina-autore.php" onsubmit="return confirm(<?= json_encode($messaggioConfermaEliminazione, JSON_UNESCAPED_UNICODE) ?>)">
        <input type="hidden" name="id" value="<?= (int) $autore['id'] ?>">
        <button type="submit">Elimina autore</button>
    </form>
</div>

</main>
<?php require_once __DIR__ . '/parti/piede.php'; ?>