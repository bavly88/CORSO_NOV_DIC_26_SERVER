   <?php
    /*
 * 01 - SESSIONI
 * Esecuzione: dalla cartella sito:  php -S localhost:8000
 *
 * HTTP non ha memoria: ogni richiesta è indipendente dalle altre.
 * session_start() (in funzioni.php) crea una sessione: il server manda al browser un cookie
 * con un codice casuale, il browser lo rimanda a ogni richiesta, e PHP ritrova i dati
 * di $_SESSION, che stanno SUL SERVER.
 * F12 > Application (Chrome) > Cookies: il cookie si chiama PHPSESSID.
 */
    require __DIR__ . '/funzioni.php';
    // un contatore che sopravvive tra una richiesta e l'altra
    $_SESSION['visite'] = ($_SESSION['visite'] ?? 0) + 1;

    $titoloPagina = 'Home';
    require __DIR__ . '/parti/testata.php';
    ?>

   <h1>Bookshelf</h1>
   <p>Hai aperto questa pagina <strong><?= $_SESSION['visite'] ?></strong> volte in questa sessione.</p>
   <p>Prova ad aprirla in una finestra in incognito: il contatore riparte da 1.</p>

   <?php require __DIR__ . '/parti/piede.php'; ?>