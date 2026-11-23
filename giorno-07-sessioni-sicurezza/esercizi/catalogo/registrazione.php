<?php
declare(strict_types=1);

// 03 - REGISTRAZIONE
require __DIR__ . '/db.php';

$errori = [];
$nome = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($nome === '') {
        $errori[] = 'Il nome è obbligatorio.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori[] = 'Email non valida.';
    }
    if (mb_strlen($password) < 8) {
        $errori[] = 'La password deve avere almeno 8 caratteri.';
    }

    if (!$errori) {
        $stmt = db()->prepare("SELECT id FROM utenti WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errori[] = 'Email già registrata.';
        }
    }

    if (!$errori) {
        db()->prepare("INSERT INTO utenti (nome, email, password_hash) VALUES (?, ?, ?)")
            ->execute([$nome, $email, password_hash($password, PASSWORD_DEFAULT)]);
        redirect('login.php');
    }
}

$titoloPagina = 'Registrati';
require __DIR__ . '/parti/testata.php';
?>

<h1>Registrati</h1>
<?php foreach ($errori as $errore) { ?><p class="errore"><?= e($errore) ?></p><?php } ?>
<form method="post">
    <?= csrfCampo() ?>
    <label>Nome <input name="nome" value="<?= e($nome) ?>"></label>
    <label>Email <input type="email" name="email" value="<?= e($email) ?>"></label>
    <label>Password <input type="password" name="password"></label>
    <button type="submit">Registrati</button>
</form>

<?php require __DIR__ . '/parti/piede.php'; ?>