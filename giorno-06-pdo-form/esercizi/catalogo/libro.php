<?php
<<<<<<< HEAD
declare(strict_types=1);

=======
// Il dettaglio di un libro: libro.php?id=8
>>>>>>> fe5e7dd10dbe96dc5676fa4171b47fc0645745f0
require __DIR__ . '/db.php';

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
<<<<<<< HEAD
    ?>
    <div class="scheda-autore-top">
        <h1>Libro non trovato</h1>
        <a href="index.php" class="btn-secondario">← Catalogo</a>
    </div>
    <p class="vuoto-box">Il libro richiesto non esiste o il link non è valido.</p>
    <?php
=======
    echo '<h1>Libro non trovato</h1>';
>>>>>>> fe5e7dd10dbe96dc5676fa4171b47fc0645745f0
    require __DIR__ . '/parti/piede.php';
    exit;
}

<<<<<<< HEAD
$stmt = db()->prepare(
    "SELECT a.id, a.nome, a.cognome
=======
// Una seconda query per gli autori (relazione N-N)
$stmt = db()->prepare(
    "SELECT a.nome, a.cognome, a.id
>>>>>>> fe5e7dd10dbe96dc5676fa4171b47fc0645745f0
     FROM autori a JOIN libri_autori la ON la.autore_id = a.id
     WHERE la.libro_id = ?"
);
$stmt->execute([$id]);
$autori = $stmt->fetchAll();

<<<<<<< HEAD
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
        <a href="modifica.php?id=<?= (int) $libro['id'] ?>" class="btn-secondario">Modifica</a>
        <a href="index.php" class="btn-secondario">← Catalogo</a>
    </div>
</div>

<dl class="scheda-anagrafica">
    <dt>Genere</dt>
    <dd><?= e(!empty($libro['genere']) ? (string) $libro['genere'] : 'Non indicato') ?></dd>

    <dt>Anno di pubblicazione</dt>
    <dd><?= e(!empty($libro['anno']) ? (string) $libro['anno'] : 'Non indicato') ?></dd>

    <dt>Numero di pagine</dt>
    <dd><?= e(!empty($libro['pagine']) ? (int) $libro['pagine'] . ' pagine' : 'Non indicato') ?></dd>
</dl>

<div class="zona-elimina">
    <form method="post" action="elimina.php" onsubmit="return confirm('Eliminare il libro?')">
        <input type="hidden" name="id" value="<?= (int) $libro['id'] ?>">
        <button type="submit">Elimina</button>
    </form>
</div>

<?php require __DIR__ . '/parti/piede.php'; ?>
=======
$titoloPagina = $libro['titolo'];
require __DIR__ . '/parti/testata.php';
?>

<h1><?= e($libro['titolo']) ?></h1>
<p>
    <?php foreach ($autori as $a) { ?>
        <a href="autore.php?id=<?= (int) $a['id'] ?>"><?= e($a['nome'] . ' ' . $a['cognome']) ?></a><br>
    <?php } ?>
    <!-- SOLUZIONE ALTERNATIVA -->
    <?php 
    // $linkAutori = [];
    // foreach ($autori as $a) {
    // $nomeAut = trim((string) ($a['nome'] ?? '') . ' ' . (string) ($a['cognome'] ?? ''));
    // $linkAutori[] = '<a href="autore.php?id=' . (int) $a['id'] . '">' . e($nomeAut) . '</a>';
    // }
    // echo implode(', ', $linkAutori); 
    ?>
</p>
<p>Anno: <?= $libro['anno'] ?? '-' ?> - Pagine: <?= $libro['pagine'] ?? '-' ?> - Genere: <?= e($libro['genere'] ?? '-') ?></p>
<p><a href="index.php">Torna al catalogo</a></p>

<?php require __DIR__ . '/parti/piede.php'; ?>


>>>>>>> fe5e7dd10dbe96dc5676fa4171b47fc0645745f0
