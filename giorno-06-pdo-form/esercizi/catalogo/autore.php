<?php
<<<<<<< HEAD
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
=======
// Il dettaglio di un autore: autore.php?id=8
require __DIR__ . '/db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare(
    "SELECT a.nome, a.cognome, a.nazionalita, a.anno_nascita FROM autori a WHERE id=?"
);
$stmt->execute([$id]);
$autore = $stmt->fetch();


if ($autore === false) {
    http_response_code(404);
    $titoloPagina = 'Autore non trovato';
    require __DIR__ . '/parti/testata.php';
    echo '<h1>Autore non trovato</h1>';
    require __DIR__ . '/parti/piede.php';
    exit;
}

// // Una seconda query per i libri (relazione N-N)
$stmt = db()->prepare(
    "SELECT l.* FROM libri l
LEFT JOIN libri_autori la ON la.libro_id=l.id
WHERE la.autore_id=?"
);
$stmt->execute([$id]);
$libri = $stmt->fetchAll();

$titoloPagina = $autore['cognome'];
require __DIR__ . '/parti/testata.php';
?>

<h1><?=e($autore['cognome']) . " " . e($autore['nome']) ?></h1>

<p>Anno di nascita: <?= $autore['anno_nascita'] ?? '-' ?> - Nazionalità: <?= $autore['nazionalita'] ?? '-' ?></p>

<table>
    <tr><th>Titolo</th>
    <th>Genere</th>
    <th>Anno</th></tr>
    <?php foreach ($libri as $libro) { ?>
        <tr>
            <td><a href="libro.php?id=<?= $libro['id'] ?>"><?= e($libro['titolo']) ?></a></td>
            <td><?= $libro['anno'] ?></td>
        </tr>
    <?php } ?>
</table>
<p><a href="autori.php">Torna agli autori</a></p>

<?php require __DIR__ . '/parti/piede.php'; ?>
>>>>>>> fe5e7dd10dbe96dc5676fa4171b47fc0645745f0
