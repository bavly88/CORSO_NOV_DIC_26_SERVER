<?php
// Esercizio 6.3 - Nuovo autore
require __DIR__ . '/db.php';

$errori = [];
$dati = ['nome' => '', 'cognome' => '', 'nazionalita' => '', 'anno_nascita' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dati as $campo => $vuoto) {
        $dati[$campo] = trim($_POST[$campo] ?? '');
    }

    foreach (['nome', 'cognome'] as $campo) {
        if ($dati[$campo] === '') {
            $errori[$campo] = 'Campo obbligatorio.';
        } elseif (mb_strlen($dati[$campo]) > 60) {
            $errori[$campo] = 'Al massimo 60 caratteri.';
        }
    }

    $anno = $dati['anno_nascita'];
    if ($anno !== '' && (!ctype_digit($anno) || $anno < 1000 || $anno > date('Y'))) {
        $errori['anno_nascita'] = 'Anno non valido.';
    }

    if (count($errori) === 0) {
        $stmt = db()->prepare(
            "INSERT INTO autori (nome, cognome, nazionalita, anno_nascita) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $dati['nome'],
            $dati['cognome'],
            $dati['nazionalita'] === '' ? null : $dati['nazionalita'],
            $anno === '' ? null : (int) $anno,
        ]);
        header('Location: autore.php?id=' . db()->lastInsertId());
        exit;
    }
}

$titoloPagina = 'Nuovo autore';
require __DIR__ . '/parti/testata.php';

$campi = ['nome' => 'Nome', 'cognome' => 'Cognome', 'nazionalita' => 'Nazionalità', 'anno_nascita' => 'Anno di nascita'];
?>

<h1>Nuovo autore</h1>
<form method="post">
    <?php foreach ($campi as $campo => $etichetta) { ?>
        <label><?= $etichetta ?>
            <input type="<?= $campo === 'anno_nascita' ? 'number' : 'text' ?>" name="<?= $campo ?>" value="<?= e($dati[$campo]) ?>">
        </label>
        <?php if (isset($errori[$campo])) { ?><p class="errore"><?= $errori[$campo] ?></p><?php } ?>
    <?php } ?>
    <button type="submit">Salva</button>
</form>

<?php require __DIR__ . '/parti/piede.php'; ?>
<?php
// Con il nome <b>Mario</b> nella pagina dell'autore compare il testo "<b>Mario</b>",
// non Mario in grassetto: e() ha trasformato < e > in entità.
