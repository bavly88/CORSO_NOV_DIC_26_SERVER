<?php
// 03 - LOGOUT: con POST e token CSRF, perché modifica lo stato (come un'eliminazione)
require __DIR__ . '/funzioni.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();

    // 1. Svuota $_SESSION IN MEMORIA, cioè l'array che questo script vede.
    //    session_destroy() non lo fa: senza questa riga, fino alla fine
    //    della richiesta $_SESSION conterrebbe ancora i dati dell'utente.
    $_SESSION = [];

    // 2. Cancella il cookie PHPSESSID NEL BROWSER.
    //    Né $_SESSION = [] né session_destroy() lo toccano: senza queste righe
    //    il browser continuerebbe a inviare il vecchio ID, e con
    //    session.use_strict_mode = 0 PHP lo riuserebbe per una sessione nuova.
    //    Una data nel passato dice al browser di eliminare il cookie.
    //    Path, domain, secure e httponly devono essere gli stessi del cookie
    //    originale, altrimenti il browser lo considera un cookie diverso:
    //    per questo si leggono con session_get_cookie_params().
    $p = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 3600,
        $p['path'],
        $p['domain'],
        $p['secure'],
        $p['httponly']
    );

    // 3. Elimina i dati SUL SERVER, cioè il file sess_<ID>.
    //    Non svuota $_SESSION (vedi punto 1) e non cancella il cookie (vedi punto 2).
    session_destroy();
}
redirect('index.php');
