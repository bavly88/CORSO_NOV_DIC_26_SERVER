<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

if ($id === false || $id === null) {
    http_response_code(404);
    $titoloPagina = 'Libro non trovato';
    require __DIR__ . '/parti/testata.php';
    ?>
    <div class="scheda-autore-top">
        <h1>404 - Libro non trovato</h1>
        <a href="index.php" class="btn-secondario">← Catalogo</a>
    </div>
    <p class="vuoto-box">Il libro richiesto non esiste o il link non è valido.</p>
    <?php
    require __DIR__ . '/parti/piede.php';
    exit;
}

$stmt = db()->prepare('SELECT * FROM libri WHERE id = ?');
$stmt->execute([$id]);
$libro = $stmt->fetch();

if ($libro === false) {
    http_response_code(404);
    $titoloPagina = 'Libro non trovato';
    require __DIR__ . '/parti/testata.php';
    ?>
    <div class="scheda-autore-top">
        <h1>404 - Libro non trovato</h1>
        <a href="index.php" class="btn-secondario">← Catalogo</a>
    </div>
    <p class="vuoto-box">Il libro richiesto non esiste o il link non è valido.</p>
    <?php
    require __DIR__ . '/parti/piede.php';
    exit;
}

$generi = db()->query('SELECT id, nome FROM generi ORDER BY nome')->fetchAll();

$annoCorrente = (int) date('Y');

$valori = [
    'titolo' => (string) ($libro['titolo'] ?? ''),
    'anno' => isset($libro['anno']) && $libro['anno'] !== null ? (string) $libro['anno'] : '',
    'pagine' => isset($libro['pagine']) && $libro['pagine'] !== null ? (string) $libro['pagine'] : '',
    'genere_id' => isset($libro['genere_id']) && $libro['genere_id'] !== null ? (string) $libro['genere_id'] : '',
];

$errori = [
    'titolo' => '',
    'anno' => '',
    'pagine' => '',
    'genere_id' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valori['titolo'] = trim((string) ($_POST['titolo'] ?? ''));
    $valori['anno'] = trim((string) ($_POST['anno'] ?? ''));
    $valori['pagine'] = trim((string) ($_POST['pagine'] ?? ''));
    $valori['genere_id'] = trim((string) ($_POST['genere_id'] ?? ''));

    if ($valori['titolo'] === '') {
        $errori['titolo'] = 'Il titolo è obbligatorio.';
    } elseif (mb_strlen($valori['titolo'], 'UTF-8') > 150) {
        $errori['titolo'] = 'Il titolo non può superare i 150 caratteri.';
    }

    $annoInt = null;
    if ($valori['anno'] !== '') {
        $annoFiltrato = filter_var($valori['anno'], FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1000,
                'max_range' => $annoCorrente,
            ],
        ]);
        if ($annoFiltrato === false) {
            $errori['anno'] = "L'anno deve essere un numero tra 1000 e {$annoCorrente}.";
        } else {
            $annoInt = (int) $annoFiltrato;
        }
    }

    $pagineInt = null;
    if ($valori['pagine'] !== '') {
        $pagineFiltrate = filter_var($valori['pagine'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        if ($pagineFiltrate === false) {
            $errori['pagine'] = 'Il numero di pagine deve essere un intero maggiore di 0.';
        } else {
            $pagineInt = (int) $pagineFiltrate;
        }
    }

    $genereIdInt = null;
    if ($valori['genere_id'] !== '') {
        $genereFiltrato = filter_var($valori['genere_id'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        if ($genereFiltrato === false) {
            $errori['genere_id'] = 'Genere non valido.';
        } else {
            $stmtGenere = db()->prepare('SELECT id FROM generi WHERE id = ?');
            $stmtGenere->execute([(int) $genereFiltrato]);
            if ($stmtGenere->fetch() === false) {
                $errori['genere_id'] = 'Il genere selezionato non esiste.';
            } else {
                $genereIdInt = (int) $genereFiltrato;
            }
        }
    }

    if (implode('', $errori) === '') {
        $stmtUpdate = db()->prepare(
            'UPDATE libri
             SET titolo = ?, anno = ?, pagine = ?, genere_id = ?
             WHERE id = ?'
        );
        $stmtUpdate->execute([
            $valori['titolo'],
            $annoInt,
            $pagineInt,
            $genereIdInt,
            $id,
        ]);

        header("Location: libro.php?id={$id}");
        exit;
    }
}

$titoloPagina = 'Modifica libro';
require __DIR__ . '/parti/testata.php';
?>

<div class="intestazione-autori">
    <h1>Modifica libro</h1>
    <a href="libro.php?id=<?= $id ?>" class="btn-secondario">← Scheda libro</a>
</div>

<form method="post" action="modifica.php?id=<?= $id ?>" class="form-moderno" novalidate>
    <section class="blocco-moderno">
        <div class="blocco-header">
            <h2>Dettagli del libro</h2>
            <span id="anteprima-titolo-modifica"><?= e($valori['titolo'] !== '' ? $valori['titolo'] : (string) ($libro['titolo'] ?? '')) ?></span>
        </div>

        <div class="campo-gruppo">
            <label for="titolo">Titolo *</label>
            <input
                type="text"
                id="titolo"
                name="titolo"
                maxlength="150"
                placeholder="Es. A ciascuno il suo"
                value="<?= e($valori['titolo']) ?>"
                required
            >
            <?php if ($errori['titolo'] !== ''): ?>
                <span class="errore"><?= e($errori['titolo']) ?></span>
            <?php endif; ?>
        </div>

        <div class="griglia-2">
            <div class="campo-gruppo">
                <label for="anno">Anno di pubblicazione</label>
                <input
                    type="number"
                    id="anno"
                    name="anno"
                    min="1000"
                    max="<?= $annoCorrente ?>"
                    placeholder="Es. 1966"
                    value="<?= e($valori['anno']) ?>"
                >
                <?php if ($errori['anno'] !== ''): ?>
                    <span class="errore"><?= e($errori['anno']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo-gruppo">
                <label for="pagine">Numero di pagine</label>
                <input
                    type="number"
                    id="pagine"
                    name="pagine"
                    min="1"
                    placeholder="Es. 160"
                    value="<?= e($valori['pagine']) ?>"
                >
                <?php if ($errori['pagine'] !== ''): ?>
                    <span class="errore"><?= e($errori['pagine']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="campo-gruppo">
            <label for="genere_id">Genere</label>
            <select id="genere_id" name="genere_id">
                <option value="">-- Nessun genere --</option>
                <?php foreach ($generi as $genere): ?>
                    <?php $gid = (string) $genere['id']; ?>
                    <option value="<?= e($gid) ?>" <?= $valori['genere_id'] === $gid ? 'selected' : '' ?>>
                        <?= e((string) $genere['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($errori['genere_id'] !== ''): ?>
                <span class="errore"><?= e($errori['genere_id']) ?></span>
            <?php endif; ?>
        </div>
    </section>

    <div class="form-azioni">
        <button type="submit">Salva modifiche</button>
        <a href="libro.php?id=<?= $id ?>">Annulla</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const titoloInput = document.getElementById('titolo');
    const anteprima = document.getElementById('anteprima-titolo-modifica');
    const titoloOriginale = anteprima ? anteprima.textContent.trim() : 'Modifica libro';

    if (titoloInput && anteprima) {
        titoloInput.addEventListener('input', function () {
            const val = titoloInput.value.trim();
            anteprima.textContent = val !== '' ? val : titoloOriginale;
        });
    }
});
</script>

<?php require __DIR__ . '/parti/piede.php'; ?>