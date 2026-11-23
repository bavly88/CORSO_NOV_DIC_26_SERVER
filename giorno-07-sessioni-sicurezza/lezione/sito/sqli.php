<?php
/*
 * 04 - SQL INJECTION
 * Un login scritto MALE (vulnerabile) e lo stesso scritto BENE.
 *
 * La versione vulnerabile fa due errori, apposta:
 *  1. incolla email e password dentro il testo della query;
 *  2. confronta la password direttamente nella query (password_hash = '...'):
 *     con le password salvate come hash, nemmeno un utente vero con la password giusta
 *     riesce a entrare. Si entra solo con l'attacco.
 *
 * Prove nel form vulnerabile:
 *  A. Email:     ' OR '1'='1' --      (con uno SPAZIO dopo i due trattini)
 *     Password:  qualsiasi
 *     -> WHERE email = '' OR '1'='1' -- ' AND password_hash = '...'
 *        '1'='1' è sempre vero e -- trasforma in commento il controllo della password.
 *  B. Email:     qualsiasi
 *     Password:  ' OR '1'='1
 *     -> WHERE email = 'x' AND password_hash = '' OR '1'='1'
 *        AND viene valutato prima di OR: (falso AND falso) OR vero = vero, per ogni riga.
 *  C. Email:     un'email registrata seguita da  ' --   (con lo spazio finale)
 *     -> si entra come QUELL'utente, senza conoscere la password.
 *
 * Nel form sicuro le stesse prove danno "Email o password non corrette".
 */
require __DIR__ . '/funzioni.php';

$esito = null;
$sqlEseguito = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (($_POST['versione'] ?? '') === 'vulnerabile') {
        // SBAGLIATO: il testo dell'utente viene incollato dentro la query
        $sqlEseguito = "SELECT nome FROM utenti WHERE email = '$email' AND password_hash = '$password'";
        try {
            $utente = db()->query($sqlEseguito)->fetch();
        } catch (PDOException $e) {
            $utente = false;
            $esito = 'Errore SQL: ' . $e->getMessage();
        }
    } else {
        // GIUSTO: prepared statement per cercare l'utente, password_verify per la password
        // (come in login.php)
        $sqlEseguito = "SELECT nome, password_hash FROM utenti WHERE email = ?";
        $stmt = db()->prepare($sqlEseguito);
        $stmt->execute([$email]);
        $utente = $stmt->fetch();
        if ($utente && !password_verify($password, $utente['password_hash'])) {
            $utente = false;
        }
    }

    $esito ??= $utente ? "Accesso riuscito come: {$utente['nome']}" : 'Email o password non corrette';
}

$titoloPagina = 'SQL injection';
require __DIR__ . '/parti/testata.php';
?>

<h1>SQL injection</h1>
<!-- crea due form uguali uno vulnerabile e uno sicuro -->
<?php foreach (['vulnerabile', 'sicuro'] as $versione) { ?>
    <form method="post">
        <h2>Versione <?= $versione ?></h2>
        <input type="hidden" name="versione" value="<?= $versione ?>">
        <label>Email <input name="email"></label>
        <!-- type="text" e non "password": così in aula si vede cosa si sta scrivendo -->
        <label>Password <input name="password"></label>
        <button type="submit">Accedi</button>
    </form>
<?php } ?>

<?php if ($esito) { ?>
    <p class="attenzione"><strong><?= e($esito) ?></strong><br>Query: <code><?= e($sqlEseguito) ?></code></p>
<?php } ?>

<?php require __DIR__ . '/parti/piede.php'; ?>
