<?php
declare(strict_types=1);

require __DIR__ . '/db.php'; // o auth.php se richiediLogin() è lì
richiediLogin();

$autoriDisponibili = db()
    ->query('SELECT id, cognome, nome FROM autori ORDER BY cognome ASC, nome ASC')
    ->fetchAll();

$generi = db()
    ->query('SELECT id, nome FROM generi ORDER BY nome ASC')
    ->fetchAll();

$annoCorrente = (int) date('Y');

$valori = [
    'titolo' => '',
    'anno' => '',
    'pagine' => '',
    'genere_id' => '',
    'autori' => [],
    'nuovo_autore_nome' => '',
    'nuovo_autore_cognome' => '',
    'nuovo_autore_nazionalita' => '',
    'nuovo_autore_anno_nascita' => '',
];

$errori = [
    'titolo' => '',
    'anno' => '',
    'pagine' => '',
    'genere_id' => '',
    'autori' => '',
    'nuovo_autore' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();

    $valori['titolo'] = trim((string) ($_POST['titolo'] ?? ''));
    $valori['anno'] = trim((string) ($_POST['anno'] ?? ''));
    $valori['pagine'] = trim((string) ($_POST['pagine'] ?? ''));
    $valori['genere_id'] = trim((string) ($_POST['genere_id'] ?? ''));

    $valori['nuovo_autore_nome'] = trim((string) ($_POST['nuovo_autore_nome'] ?? ''));
    $valori['nuovo_autore_cognome'] = trim((string) ($_POST['nuovo_autore_cognome'] ?? ''));
    $valori['nuovo_autore_nazionalita'] = trim((string) ($_POST['nuovo_autore_nazionalita'] ?? ''));
    $valori['nuovo_autore_anno_nascita'] = trim((string) ($_POST['nuovo_autore_anno_nascita'] ?? ''));

    $autoriRaw = $_POST['autori'] ?? [];
    $valori['autori'] = is_array($autoriRaw)
        ? array_values(array_unique(array_map(static fn($v): string => trim((string) $v), $autoriRaw)))
        : [];

    // 1. Validazione libro
    if ($valori['titolo'] === '') {
        $errori['titolo'] = 'Il titolo è obbligatorio.';
    }

    $annoInt = null;
    if ($valori['anno'] !== '') {
        $annoFiltrato = filter_var($valori['anno'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1000, 'max_range' => $annoCorrente],
        ]);
        if ($annoFiltrato === false) {
            $errori['anno'] = "L'anno deve essere tra 1000 e {$annoCorrente}.";
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
            $errori['pagine'] = 'Il numero di pagine deve essere maggiore di 0.';
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

    // 2. Validazione autori già esistenti selezionati
    $autoriIdsValidi = [];
    if (!empty($valori['autori'])) {
        $mappaDisponibili = [];
        foreach ($autoriDisponibili as $aut) {
            $mappaDisponibili[(int) $aut['id']] = true;
        }

        foreach ($valori['autori'] as $idStr) {
            $idAutore = filter_var($idStr, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($idAutore === false || !isset($mappaDisponibili[(int) $idAutore])) {
                $errori['autori'] = 'Uno o più autori selezionati non sono validi.';
                break;
            }
            $autoriIdsValidi[] = (int) $idAutore;
        }
    }

    // 3. Validazione eventuale nuovo autore compilato al volo
    $vuoleNuovoAutore = (
        $valori['nuovo_autore_nome'] !== '' ||
        $valori['nuovo_autore_cognome'] !== '' ||
        $valori['nuovo_autore_nazionalita'] !== '' ||
        $valori['nuovo_autore_anno_nascita'] !== ''
    );

    $nuovoAutoreAnnoInt = null;
    if ($vuoleNuovoAutore) {
        if ($valori['nuovo_autore_nome'] === '' || $valori['nuovo_autore_cognome'] === '') {
            $errori['nuovo_autore'] = 'Per creare un nuovo autore inserisci sia nome che cognome.';
        } elseif (mb_strlen($valori['nuovo_autore_nome'], 'UTF-8') > 60 || mb_strlen($valori['nuovo_autore_cognome'], 'UTF-8') > 60) {
            $errori['nuovo_autore'] = 'Nome e cognome non possono superare i 60 caratteri.';
        } elseif ($valori['nuovo_autore_anno_nascita'] !== '') {
            $annoAutoreFiltrato = filter_var($valori['nuovo_autore_anno_nascita'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1000, 'max_range' => $annoCorrente],
            ]);
            if ($annoAutoreFiltrato === false) {
                $errori['nuovo_autore'] = "L'anno di nascita dell'autore deve essere tra 1000 e {$annoCorrente}.";
            } else {
                $nuovoAutoreAnnoInt = (int) $annoAutoreFiltrato;
            }
        }
    }

    // 4. Salvataggio atomico in transazione
    if (implode('', $errori) === '') {
        $pdo = db();
        $pdo->beginTransaction();

        try {
            // Inserisci il libro
            $stmtLibro = $pdo->prepare(
                'INSERT INTO libri (titolo, anno, pagine, genere_id) VALUES (?, ?, ?, ?)'
            );
            $stmtLibro->execute([
                $valori['titolo'],
                $annoInt,
                $pagineInt,
                $genereIdInt,
            ]);
            $nuovoLibroId = (int) $pdo->lastInsertId();

            // Se compilato, salva il nuovo autore in `autori` (evitando duplicati esatti nome+cognome)
            if ($vuoleNuovoAutore) {
                $stmtCercaAutore = $pdo->prepare(
                    'SELECT id FROM autori WHERE nome = ? AND cognome = ? LIMIT 1'
                );
                $stmtCercaAutore->execute([
                    $valori['nuovo_autore_nome'],
                    $valori['nuovo_autore_cognome'],
                ]);
                $autoreEsistente = $stmtCercaAutore->fetch();

                if ($autoreEsistente !== false) {
                    $nuovoAutoreId = (int) $autoreEsistente['id'];
                } else {
                    $stmtNuovoAutore = $pdo->prepare(
                        'INSERT INTO autori (nome, cognome, nazionalita, anno_nascita) VALUES (?, ?, ?, ?)'
                    );
                    $stmtNuovoAutore->execute([
                        $valori['nuovo_autore_nome'],
                        $valori['nuovo_autore_cognome'],
                        $valori['nuovo_autore_nazionalita'] !== '' ? $valori['nuovo_autore_nazionalita'] : null,
                        $nuovoAutoreAnnoInt,
                    ]);
                    $nuovoAutoreId = (int) $pdo->lastInsertId();
                }

                $autoriIdsValidi[] = $nuovoAutoreId;
            }

            // Rimuovi eventuali duplicati e collega tutti gli autori al libro
            $autoriIdsValidi = array_values(array_unique($autoriIdsValidi));
            if (!empty($autoriIdsValidi)) {
                $stmtLink = $pdo->prepare(
                    'INSERT INTO libri_autori (libro_id, autore_id) VALUES (?, ?)'
                );
                foreach ($autoriIdsValidi as $autoreId) {
                    $stmtLink->execute([$nuovoLibroId, $autoreId]);
                }
            }

      $pdo->commit();
            $_SESSION['flash'] = 'Libro salvato.';
            header("Location: libro.php?id={$nuovoLibroId}");
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}

$apriNuovoAutore = (
    $errori['nuovo_autore'] !== '' ||
    $valori['nuovo_autore_nome'] !== '' ||
    $valori['nuovo_autore_cognome'] !== '' ||
    $valori['nuovo_autore_nazionalita'] !== '' ||
    $valori['nuovo_autore_anno_nascita'] !== ''
);

$titoloPagina = 'Nuovo libro';
require __DIR__ . '/parti/testata.php';
?>

<div class="intestazione-autori">
    <h1>Nuovo libro</h1>
    <a href="index.php" class="btn-secondario">← Catalogo</a>
</div>

<form method="post" action="nuovo.php" class="form-moderno" novalidate>
     <?= csrfCampo() ?>
    <section class="blocco-moderno">
        <div class="blocco-header">
            <h2>Informazioni principali</h2>
            <span>Compila i dettagli del libro</span>
        </div>

        <div class="campo-gruppo">
            <label for="titolo">Titolo *</label>
            <input type="text" id="titolo" name="titolo" placeholder="Es. Il barone rampante" value="<?= e($valori['titolo']) ?>" required>
            <?php if ($errori['titolo'] !== ''): ?><span class="errore"><?= e($errori['titolo']) ?></span><?php endif; ?>
        </div>

        <div class="griglia-2">
            <div class="campo-gruppo">
                <label for="anno">Anno di pubblicazione</label>
                <input type="number" id="anno" name="anno" min="1000" max="<?= $annoCorrente ?>" placeholder="Es. 1957" value="<?= e($valori['anno']) ?>">
                <?php if ($errori['anno'] !== ''): ?><span class="errore"><?= e($errori['anno']) ?></span><?php endif; ?>
            </div>

            <div class="campo-gruppo">
                <label for="pagine">Numero di pagine</label>
                <input type="number" id="pagine" name="pagine" min="1" placeholder="Es. 240" value="<?= e($valori['pagine']) ?>">
                <?php if ($errori['pagine'] !== ''): ?><span class="errore"><?= e($errori['pagine']) ?></span><?php endif; ?>
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
            <?php if ($errori['genere_id'] !== ''): ?><span class="errore"><?= e($errori['genere_id']) ?></span><?php endif; ?>
        </div>
    </section>

    <section class="blocco-moderno">
        <div class="blocco-header">
            <h2>Autori</h2>
            <span id="contatore-autori-selezionati"><?= count($valori['autori']) ?> selezionati</span>
        </div>

        <div class="chip-autori" role="group" aria-label="Seleziona autori già presenti">
            <?php foreach ($autoriDisponibili as $autore): ?>
                <?php
                    $aid = (string) $autore['id'];
                    $nomeAutore = trim((string) $autore['cognome'] . ' ' . (string) $autore['nome']);
                    $checked = in_array($aid, $valori['autori'], true);
                ?>
                <label class="chip-autore <?= $checked ? 'is-selected' : '' ?>">
                    <input type="checkbox" name="autori[]" value="<?= e($aid) ?>" <?= $checked ? 'checked' : '' ?>>
                    <span><?= e($nomeAutore) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php if ($errori['autori'] !== ''): ?><span class="errore"><?= e($errori['autori']) ?></span><?php endif; ?>

        <div class="toggle-nuovo-autore-wrap">
            <button type="button" class="btn-toggle-autore" id="btn-toggle-autore" aria-expanded="<?= $apriNuovoAutore ? 'true' : 'false' ?>" aria-controls="pannello-nuovo-autore">
                <?= $apriNuovoAutore ? '− Nascondi nuovo autore' : '+ Aggiungi un nuovo autore al volo' ?>
            </button>
        </div>

        <div class="pannello-nuovo-autore <?= $apriNuovoAutore ? 'is-open' : '' ?>" id="pannello-nuovo-autore">
            <?php if ($errori['nuovo_autore'] !== ''): ?>
                <span class="errore"><?= e($errori['nuovo_autore']) ?></span>
            <?php endif; ?>

            <div class="griglia-2">
                <div class="campo-gruppo">
                    <label for="nuovo_autore_nome">Nome</label>
                    <input type="text" id="nuovo_autore_nome" name="nuovo_autore_nome" maxlength="60" placeholder="Es. Italo" value="<?= e($valori['nuovo_autore_nome']) ?>">
                </div>

                <div class="campo-gruppo">
                    <label for="nuovo_autore_cognome">Cognome</label>
                    <input type="text" id="nuovo_autore_cognome" name="nuovo_autore_cognome" maxlength="60" placeholder="Es. Calvino" value="<?= e($valori['nuovo_autore_cognome']) ?>">
                </div>
            </div>

            <div class="griglia-2">
                <div class="campo-gruppo">
                    <label for="nuovo_autore_nazionalita">Nazionalità</label>
                    <input type="text" id="nuovo_autore_nazionalita" name="nuovo_autore_nazionalita" placeholder="Es. Italiana" value="<?= e($valori['nuovo_autore_nazionalita']) ?>">
                </div>

                <div class="campo-gruppo">
                    <label for="nuovo_autore_anno_nascita">Anno di nascita</label>
                    <input type="number" id="nuovo_autore_anno_nascita" name="nuovo_autore_anno_nascita" min="1000" max="<?= $annoCorrente ?>" placeholder="Es. 1923" value="<?= e($valori['nuovo_autore_anno_nascita']) ?>">
                </div>
            </div>
        </div>
    </section>

    <div class="form-azioni">
        <button type="submit">Salva libro</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chips = document.querySelectorAll('.chip-autore input[type="checkbox"]');
    const contatore = document.getElementById('contatore-autori-selezionati');

    function aggiornaChip() {
        let selezionati = 0;
        chips.forEach(function (chk) {
            const label = chk.closest('.chip-autore');
            if (chk.checked) {
                selezionati++;
                label.classList.add('is-selected');
            } else {
                label.classList.remove('is-selected');
            }
        });
        if (contatore) {
            contatore.textContent = selezionati + (selezionati === 1 ? ' selezionato' : ' selezionati');
        }
    }

    chips.forEach(function (chk) {
        chk.addEventListener('change', aggiornaChip);
    });

    const btnToggle = document.getElementById('btn-toggle-autore');
    const pannello = document.getElementById('pannello-nuovo-autore');

    if (btnToggle && pannello) {
        btnToggle.addEventListener('click', function () {
            const aperto = pannello.classList.toggle('is-open');
            btnToggle.setAttribute('aria-expanded', aperto ? 'true' : 'false');
            btnToggle.textContent = aperto ? '− Nascondi nuovo autore' : '+ Aggiungi un nuovo autore al volo';
        });
    }
});
</script>

<?php require __DIR__ . '/parti/piede.php'; ?>