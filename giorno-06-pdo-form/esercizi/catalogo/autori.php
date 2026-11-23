<<<<<<< HEAD
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
=======
<?php  
require __DIR__ . '/db.php';
$sql = "SELECT a.*, COUNT(la.libro_id) libri FROM autori a   
  LEFT JOIN libri_autori la ON la.autore_id = a.id
  group by a.id 
  ORDER BY a.cognome";
$stmt = db()->query($sql);
$autori = $stmt->fetchAll();
$titoloPagina = 'Autori';
require __DIR__ . '/parti/testata.php';
?>
<table>
    <tr><th>Nome</th><th>Nazionalità</th><th>Anno</th><th>Libri in catalogo</th></tr>
    <?php foreach ($autori as $a) { ?>
        <tr>
            <td><a href="autore.php?id=<?= $a['id']?>"><?= e($a['cognome']) . " " . e($a['nome']) ?></a></td>
            <td><?= e($a['nazionalita']) ?></td>
            <td><?= e($a['anno_nascita']) ?></td>
            <td><?= e($a['libri']) ?></td>
        </tr>
    <?php } ?>
</table>

<?php require __DIR__ . '/parti/piede.php'; ?>






>>>>>>> fe5e7dd10dbe96dc5676fa4171b47fc0645745f0
