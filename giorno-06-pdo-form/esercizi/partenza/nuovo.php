<?php
/*
 * NUOVO LIBRO: form con method="post", validazione, INSERT, Post/Redirect/Get
 *
 * La stessa pagina fa due cose:
 *  - richiesta GET (si apre la pagina): mostra il form vuoto;
 *  - richiesta POST (si invia il form): controlla i dati e, se sono validi, salva.
 */
require __DIR__ . '/db.php';

$generi = db()->query("SELECT id, nome FROM generi ORDER BY nome")->fetchAll();

$errori = [];
// $dati contiene i valori inseriti: servono per ripopolare il form dopo un errore
$dati = ['titolo' => '', 'anno' => '', 'pagine' => '', 'genere_id' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $_POST contiene i campi del form, per name
    $dati['titolo'] = trim($_POST['titolo'] ?? '');
    $dati['anno'] = trim($_POST['anno'] ?? '');
    $dati['pagine'] = trim($_POST['pagine'] ?? '');
    $dati['genere_id'] = $_POST['genere_id'] ?? '';

    // VALIDAZIONE SUL SERVER: sempre, anche se il form ha required.
    // Chi vuole può inviare dati senza passare dal form (F12, curl...).
    if ($dati['titolo'] === '') {
        $errori['titolo'] = 'Il titolo è obbligatorio.';
    } elseif (mb_strlen($dati['titolo']) > 200) {
        $errori['titolo'] = 'Al massimo 200 caratteri.';
    }

    if ($dati['anno'] !== '' && (!ctype_digit($dati['anno']) || $dati['anno'] < 1450 || $dati['anno'] > date('Y'))) {
        $errori['anno'] = 'Anno non valido.';
    }

    if ($dati['pagine'] !== '' && (!ctype_digit($dati['pagine']) || (int) $dati['pagine'] < 1)) {
        $errori['pagine'] = 'Le pagine devono essere un numero maggiore di zero.';
    }

    // il genere deve essere uno di quelli del database
    $idGeneri = array_column($generi, 'id');
    if (!in_array((int) $dati['genere_id'], $idGeneri)) {
        $errori['genere_id'] = 'Scegli un genere.';
    }

    if (count($errori) === 0) {
        $stmt = db()->prepare(
            "INSERT INTO libri (titolo, anno, pagine, genere_id) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $dati['titolo'],
            $dati['anno'] === '' ? null : (int) $dati['anno'],     // campo vuoto -> NULL
            $dati['pagine'] === '' ? null : (int) $dati['pagine'],
            (int) $dati['genere_id'],
        ]);

        // POST/REDIRECT/GET: dopo aver salvato si manda il browser a un'altra pagina.
        // Senza, ricaricando la pagina il browser rimanderebbe il form e il libro sarebbe salvato due volte.
        header('Location: libro.php?id=' . db()->lastInsertId());
        exit;
    }
}

$titoloPagina = 'Nuovo libro';
require __DIR__ . '/parti/testata.php';
?>

<h1>Nuovo libro</h1>

<form method="post">
    <label>Titolo
        <input type="text" name="titolo" value="<?= e($dati['titolo']) ?>">
    </label>
    <?php if (isset($errori['titolo'])) { ?><p class="errore"><?= $errori['titolo'] ?></p><?php } ?>

    <label>Anno
        <input type="number" name="anno" value="<?= e($dati['anno']) ?>">
    </label>
    <?php if (isset($errori['anno'])) { ?><p class="errore"><?= $errori['anno'] ?></p><?php } ?>

    <label>Pagine
        <input type="number" name="pagine" value="<?= e($dati['pagine']) ?>">
    </label>
    <?php if (isset($errori['pagine'])) { ?><p class="errore"><?= $errori['pagine'] ?></p><?php } ?>

    <label>Genere
        <select name="genere_id">
            <option value="">-- scegli --</option>
            <?php foreach ($generi as $g) { ?>
                <option value="<?= $g['id'] ?>" <?= (int) $dati['genere_id'] === $g['id'] ? 'selected' : '' ?>>
                    <?= e($g['nome']) ?>
                </option>
            <?php } ?>
        </select>
    </label>
    <?php if (isset($errori['genere_id'])) { ?><p class="errore"><?= $errori['genere_id'] ?></p><?php } ?>

    <button type="submit">Salva</button>
</form>

<?php require __DIR__ . '/parti/piede.php'; ?>
