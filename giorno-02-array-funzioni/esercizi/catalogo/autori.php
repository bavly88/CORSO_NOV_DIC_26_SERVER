<?php
// 1. Definiamo la funzione e() prima di tutto
if (!function_exists('e')) {
    function e(string $testo): string {
        return htmlspecialchars($testo, ENT_QUOTES, 'UTF-8');
    }
}

// 2. Definiamo la variabile che la testata si aspetta di trovare per il titolo della pagina
$titoloPagina = "Autori";

// 3. Ora possiamo includere la testata in sicurezza
require __DIR__ . '/parti/testata.php';

// 4. Carichiamo i libri e calcoliamo il conteggio per autore
$libri = require __DIR__ . '/libri.php';
$conteggio_autori = array_count_values(array_column($libri, 'autore'));
ksort($conteggio_autori);
$totaleAutori = count($conteggio_autori);
?>

<div class="page-header">
    <h1>Autori</h1>
    <span class="subtitle">&bull; <?= $totaleAutori ?> <?= $totaleAutori === 1 ? 'autore' : 'autori' ?></span>
</div>

<ul class="authors-grid">
    <?php foreach ($conteggio_autori as $autore => $numero_libri): ?>
        <li>
            <a href="autore.php?nome=<?= urlencode($autore) ?>" class="author-card">
                <span class="author-name"><?= e($autore) ?></span>
                <span class="author-count"><?= $numero_libri ?> <?= ($numero_libri == 1) ? 'libro' : 'libri' ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php
/*
 * Risposte alle domande del Passo 3:
 * 
 * 1. Con questo array (ottenuto tramite `array_count_values`), serve ancora `array_unique`?
 *    No, non serve. `array_count_values` raggruppa automaticamente tutti gli elementi 
 *    uguali usandoli come chiavi dell'array risultante. Poiché le chiavi di un array 
 *    in PHP sono uniche, le ripetizioni vengono eliminate "gratuitamente" e in più 
 *    abbiamo già il conteggio dei libri per ciascuno.
 * 
 * 2. Come lo ordineresti per nome?
 *    Poiché gli autori sono diventati le *chiavi* dell'array associativo, per ordinarli 
 *    in ordine alfabetico si utilizza `ksort($conteggio_autori);` (esattamente come visto 
 *    nell'esercizio 2.1 per i titoli dei libri).
 */

// Carichiamo il piede comune
require __DIR__ . '/parti/piede.php';
?>