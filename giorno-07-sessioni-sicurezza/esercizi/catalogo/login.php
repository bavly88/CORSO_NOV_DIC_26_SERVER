<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errore = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare("SELECT * FROM utenti WHERE email = ?");
    $stmt->execute([$email]);
    $utente = $stmt->fetch();

    if ($utente && password_verify($password, (string) $utente['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['utente'] = [
            'id'   => (int) $utente['id'],
            'nome' => (string) $utente['nome'],
        ];
        redirect('index.php');
    }

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