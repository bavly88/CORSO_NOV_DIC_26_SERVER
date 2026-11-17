<?php
if (!function_exists('e')) {
    function e(string $testo): string {
        return htmlspecialchars($testo, ENT_QUOTES, 'UTF-8');
    }
}

$nome = $_GET['nome'] ?? '';

$libri = require __DIR__ . '/libri.php';

$suoiLibri = array_filter($libri, function ($libro) use ($nome) {
    return $libro['autore'] === $nome;
});

if (empty($suoiLibri)) {
    http_response_code(404);
    $titoloPagina = "Autore non trovato";
} else {
    $titoloPagina = $nome;
}

require __DIR__ . '/parti/testata.php';
?>

<?php if (empty($suoiLibri)): ?>
    <div class="page-header">
        <h1>Autore non trovato</h1>
    </div>
    <p>Spiacenti, l'autore cercato non è presente nel catalogo o il parametro non è valido.</p>
<?php else: ?>
    <?php $totaleSuoiLibri = count($suoiLibri); ?>
    <div class="page-header">
        <h1><?= e($nome) ?></h1>
        <span class="subtitle">&bull; <?= $totaleSuoiLibri ?> <?= $totaleSuoiLibri === 1 ? 'libro' : 'libri' ?></span>
    </div>
    
    <ul class="authors-grid">
        <?php foreach ($suoiLibri as $libro): ?>
            <li>
                <a href="libro.php?id=<?= $libro['id'] ?>" class="author-card">
                    <span class="author-name"><?= e($libro['titolo']) ?></span>
                    <span class="author-count"><?= e($libro['genere']) ?> &bull; <?= $libro['anno'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p style="margin-top: 28px;">
    <a href="autori.php" class="btn" style="text-decoration: none; display: inline-block;">&larr; Torna agli autori</a>
</p>

<?php
/*
- Risposta Passo 6 (Perché serve `e()`):
- 
- - Cosa cambia se si toglie `e()`?
-   Se si rimuove `e()` e si passa nella query string un tag HTML (ad esempio `?nome=<b>ciao</b>`), 
-   il browser interpreta direttamente quel codice anziché stamparlo come semplice testo. 
-   Di conseguenza, la parola "ciao" apparirà in grassetto (`<b>`).
- 
- - Cosa potrebbe fare un utente malintenzionato al posto di `<b>`?
-   Un utente malintenzionato potrebbe sfruttare questa vulnerabilità (nota come XSS - Cross-Site Scripting) 
-   per iniettare codice JavaScript malevolo (es. `<script>alert('Hacked');</script>` o script più complessi). 
-   Questo codice verrebbe eseguito nel browser di chiunque apra quel link, permettendo al malintenzionato 
-   di rubare cookie di sessione, reindirizzare l'utente su siti esterni o manomettere la pagina.
-   L'uso di `e()` (che sfrutta `htmlspecialchars`) converte i caratteri speciali in entità HTML 
-   (come `&lt;` e `&gt;`), neutralizzando qualsiasi codice e mostrandolo a schermo in modo sicuro.
 */

// Carichiamo il piede comune
require __DIR__ . '/parti/piede.php';
?>