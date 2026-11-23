<?php
// 03 - LOGIN
require __DIR__ . '/db.php';

$errore = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare("SELECT * FROM utenti WHERE email = ?");
    $stmt->execute([$email]);
    $utente = $stmt->fetch();

    if ($utente && password_verify($password, $utente['password_hash'])) {
        // nuovo identificativo di sessione dopo il login (contro il furto di sessione)
        session_regenerate_id(true);
        $_SESSION['utente'] = ['id' => $utente['id'], 'nome' => $utente['nome']];
        redirect('index.php');
    }

    // Stesso messaggio se l'email non esiste o se la password è sbagliata:
    // non diciamo a un estraneo quali email sono registrate.
    $errore = 'Email o password non corrette.';
}

$titoloPagina = 'Accedi';
require __DIR__ . '/parti/testata.php';
?>

<h1>Accedi</h1>
<?php if ($errore) { ?><p class="errore"><?= e($errore) ?></p><?php } ?>
<form method="post">
    <?= csrfCampo() ?>
    <label>Email <input type="email" name="email" value="<?= e($email) ?>"></label>
    <label>Password <input type="password" name="password"></label>
    <button type="submit">Accedi</button>
</form>

<?php require __DIR__ . '/parti/piede.php'; ?>
