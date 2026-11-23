<?php
/*
 * 04 - CSRF (Cross-Site Request Forgery)
 * Un altro sito può contenere un form che invia una richiesta al NOSTRO sito.
 * Il browser allega da solo il cookie di sessione: se l'utente è loggato da noi,
 * la richiesta parte a suo nome (per esempio "esci", "elimina", "cambia email").
 *
 * La difesa: ogni form contiene un token casuale salvato in sessione (csrfCampo()),
 * e ogni POST lo controlla (csrfVerifica()). L'altro sito non conosce il token.
 *
 * Prova: invia i due form qui sotto. Il secondo NON ha il token, come il form
 * di un sito estraneo: la richiesta viene rifiutata con il codice 419.
 */
require __DIR__ . '/funzioni.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();   // se il token manca o è sbagliato, lo script si ferma qui
    $messaggio = 'Richiesta accettata: il token era corretto.';
}

$titoloPagina = 'CSRF';
require __DIR__ . '/parti/testata.php';
?>

<h1>CSRF</h1>
<?php if (isset($messaggio)) { ?><p class="attenzione"><?= $messaggio ?></p><?php } ?>

<form method="post">
    <?= csrfCampo() ?>
    <button type="submit">Form con il token</button>
</form>

<form method="post">
    <button type="submit">Form senza token (come quello di un altro sito)</button>
</form>

<p>Il token di questa sessione: <code><?= csrfToken() ?></code>. Guardalo con Ctrl+U nel campo nascosto.</p>

<?php require __DIR__ . '/parti/piede.php'; ?>
