<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$sql = "
    SELECT
        a.id,
        a.nome,
        a.cognome,
        COUNT(la.libro_id) AS totale_libri
    FROM autori a
    LEFT JOIN libri_autori la ON a.id = la.autore_id
    GROUP BY a.id, a.nome, a.cognome
    ORDER BY a.cognome ASC, a.nome ASC
";

$stmt = db()->query($sql);
$autori = $stmt->fetchAll();

$titoloPagina = 'Autori';
require_once __DIR__ . '/parti/testata.php';
?>

<div class="intestazione-autori">
    <h1>Autori</h1>
    <a href="nuovo-autore.php" class="btn-nuovo-autore">+ Nuovo autore</a>
</div>

<?php if (empty($autori)): ?>
    <p>Nessun autore presente.</p>
<?php else: ?>
    <ul class="lista-autori">
        <?php foreach ($autori as $autore): ?>
            <?php
                $id = (int) $autore['id'];
                $nomeCompleto = trim((string) $autore['cognome'] . ' ' . (string) $autore['nome']);
                $totaleLibri = (int) $autore['totale_libri'];
            ?>
            <li>
                <a href="autore.php?id=<?= $id ?>" class="autore-nome"><?= e($nomeCompleto) ?></a>
                <span class="autore-conteggio">
                    <?= $totaleLibri ?> <?= $totaleLibri === 1 ? 'libro' : 'libri' ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require_once __DIR__ . '/parti/piede.php'; ?>