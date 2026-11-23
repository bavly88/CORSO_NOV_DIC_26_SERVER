<?php
// Esercizio 6.4 - Modificare un libro: modifica.php?id=8
require __DIR__ . '/db.php';

$generi = db()->query("SELECT id, nome FROM generi ORDER BY nome")->fetchAll();

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM libri WHERE id = ?");
$stmt->execute([$id]);
$libro = $stmt->fetch();
if ($libro === false) {
    http_response_code(404);
    exit('Libro non trovato');
}

$errori = [];
// con GET: i valori attuali del libro (null diventa stringa vuota)
$dati = [
    'titolo' => $libro['titolo'],
    'anno' => (string) ($libro['anno'] ?? ''),
    'pagine' => (string) ($libro['pagine'] ?? ''),
    'genere_id' => (string) ($libro['genere_id'] ?? ''),
];

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
            "UPDATE libri SET titolo = ?, anno = ?, pagine = ?, genere_id = ? WHERE id = ?"
        );
        $stmt->execute([
            $dati['titolo'],
            $dati['anno'] === '' ? null : (int) $dati['anno'],     // campo vuoto -> NULL
            $dati['pagine'] === '' ? null : (int) $dati['pagine'],
            (int) $dati['genere_id'],
            $id,
        ]);

        header('Location: libro.php?id=' . $id);
        exit;
    }
}

$titoloPagina = 'Modifica: ' . $libro['titolo'];
require __DIR__ . '/parti/testata.php';
?>

<h1>Modifica: <?= e($libro['titolo']) ?></h1>

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
