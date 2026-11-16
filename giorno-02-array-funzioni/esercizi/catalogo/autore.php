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
    <h1>Autore non trovato</h1>
    <p>Spiacenti, l'autore cercato non è presente nel catalogo o il parametro non è valido.</p>
<?php else: ?>

    <h1><?php echo e($nome); ?></h1>
    
    <p>Elenco dei libri scritti da <?php echo e($nome); ?>:</p>
    
    <ul>
        <?php foreach ($suoiLibri as $libro): ?>
            <li>
                
                <a href="dettaglio.php?id=<?php echo urlencode($libro['titolo']); ?>">
                    <?php echo e($libro['titolo']); ?>
                </a>
                (Genere: <?php echo e($libro['genere']); ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php
/*
 * Risposta Passo 6 (Perché serve `e()`):
 * 
 * - Cosa cambia se si toglie `e()`?
 *   Se si rimuove `e()` e si passa nella query string un tag HTML (ad esempio `?nome=<b>ciao</b>`), 
 *   il browser interpreta direttamente quel codice anziché stamparlo come semplice testo. 
 *   Di conseguenza, la parola "ciao" apparirà in grassetto (`<b>`).
 * 
 * - Cosa potrebbe fare un utente malintenzionato al posto di `<b>`?
 *   Un utente malintenzionato potrebbe sfruttare questa vulnerabilità (nota come XSS - Cross-Site Scripting) 
 *   per iniettare codice JavaScript malevolo (es. `<script>alert('Hacked');</script>` o script più complessi). 
 *   Questo codice verrebbe eseguito nel browser di chiunque apra quel link, permettendo al malintenzionato 
 *   di rubare cookie di sessione, reindirizzare l'utente su siti esterni o manomettere la pagina.
 *   L'uso di `e()` (che sfrutta `htmlspecialchars`) converte i caratteri speciali in entità HTML 
 *   (come `&lt;` e `&gt;`), neutralizzando qualsiasi codice e mostrandolo a schermo in modo sicuro.
 */

// Carichiamo il piede comune
require __DIR__ . '/parti/piede.php';
?>