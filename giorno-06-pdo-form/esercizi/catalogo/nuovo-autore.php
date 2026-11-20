<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$annoCorrente = (int) date('Y');

$valori = [
    'nome' => '',
    'cognome' => '',
    'nazionalita' => '',
    'anno_nascita' => '',
];

$errori = [
    'nome' => '',
    'cognome' => '',
    'nazionalita' => '',
    'anno_nascita' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valori['nome'] = trim((string) ($_POST['nome'] ?? ''));
    $valori['cognome'] = trim((string) ($_POST['cognome'] ?? ''));
    $valori['nazionalita'] = trim((string) ($_POST['nazionalita'] ?? ''));
    $valori['anno_nascita'] = trim((string) ($_POST['anno_nascita'] ?? ''));

    if ($valori['nome'] === '') {
        $errori['nome'] = 'Il nome è obbligatorio.';
    } elseif (mb_strlen($valori['nome'], 'UTF-8') > 60) {
        $errori['nome'] = 'Il nome non può superare i 60 caratteri.';
    }

    if ($valori['cognome'] === '') {
        $errori['cognome'] = 'Il cognome è obbligatorio.';
    } elseif (mb_strlen($valori['cognome'], 'UTF-8') > 60) {
        $errori['cognome'] = 'Il cognome non può superare i 60 caratteri.';
    }

    if ($valori['nazionalita'] !== '' && mb_strlen($valori['nazionalita'], 'UTF-8') > 60) {
        $errori['nazionalita'] = 'La nazionalità non può superare i 60 caratteri.';
    }

    $annoNascitaInt = null;
    if ($valori['anno_nascita'] !== '') {
        $annoFiltrato = filter_var($valori['anno_nascita'], FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1000,
                'max_range' => $annoCorrente,
            ],
        ]);

        if ($annoFiltrato === false) {
            $errori['anno_nascita'] = "L'anno di nascita deve essere un numero intero tra 1000 e {$annoCorrente}.";
        } else {
            $annoNascitaInt = (int) $annoFiltrato;
        }
    }

    $ciSonoErrori = implode('', $errori) !== '';

    if (!$ciSonoErrori) {
        $stmt = db()->prepare(
            'INSERT INTO autori (nome, cognome, nazionalita, anno_nascita)
             VALUES (:nome, :cognome, :nazionalita, :anno_nascita)'
        );

        $stmt->execute([
            'nome' => $valori['nome'],
            'cognome' => $valori['cognome'],
            'nazionalita' => $valori['nazionalita'] !== '' ? $valori['nazionalita'] : null,
            'anno_nascita' => $annoNascitaInt,
        ]);

        $nuovoId = (int) db()->lastInsertId();
        header("Location: autore.php?id={$nuovoId}");
        exit;
    }
}

$titoloPagina = 'Nuovo autore';
require_once __DIR__ . '/parti/testata.php';
?>

<div class="intestazione-autori">
    <h1>Nuovo autore</h1>
    <a href="autori.php" class="btn-secondario">← Elenco autori</a>
</div>

<form method="post" action="nuovo-autore.php" class="form-moderno" novalidate>
    <section class="blocco-moderno">
        <div class="blocco-header">
            <h2>Anagrafica autore</h2>
            <span id="anteprima-nome-autore">Nuovo autore</span>
        </div>

        <div class="griglia-2">
            <div class="campo-gruppo">
                <label for="nome">Nome *</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="60"
                    placeholder="Es. Jorge Luis"
                    value="<?= e($valori['nome']) ?>"
                    required
                >
                <?php if ($errori['nome'] !== ''): ?>
                    <span class="errore"><?= e($errori['nome']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo-gruppo">
                <label for="cognome">Cognome *</label>
                <input
                    type="text"
                    id="cognome"
                    name="cognome"
                    maxlength="60"
                    placeholder="Es. Borges"
                    value="<?= e($valori['cognome']) ?>"
                    required
                >
                <?php if ($errori['cognome'] !== ''): ?>
                    <span class="errore"><?= e($errori['cognome']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="griglia-2">
            <div class="campo-gruppo">
                <label for="nazionalita">Nazionalità</label>
                <input
                    type="text"
                    id="nazionalita"
                    name="nazionalita"
                    maxlength="60"
                    placeholder="Es. Argentina"
                    value="<?= e($valori['nazionalita']) ?>"
                >
                <?php if ($errori['nazionalita'] !== ''): ?>
                    <span class="errore"><?= e($errori['nazionalita']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo-gruppo">
                <label for="anno_nascita">Anno di nascita</label>
                <input
                    type="number"
                    id="anno_nascita"
                    name="anno_nascita"
                    min="1000"
                    max="<?= $annoCorrente ?>"
                    placeholder="Es. 1899"
                    value="<?= e($valori['anno_nascita']) ?>"
                >
                <?php if ($errori['anno_nascita'] !== ''): ?>
                    <span class="errore"><?= e($errori['anno_nascita']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="form-azioni">
        <button type="submit">Salva autore</button>
        <a href="autori.php">Annulla</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nomeInput = document.getElementById('nome');
    const cognomeInput = document.getElementById('cognome');
    const anteprima = document.getElementById('anteprima-nome-autore');

    function aggiornaAnteprima() {
        if (!anteprima) return;
        const nome = nomeInput ? nomeInput.value.trim() : '';
        const cognome = cognomeInput ? cognomeInput.value.trim() : '';
        const completo = (nome + ' ' + cognome).trim();
        anteprima.textContent = completo !== '' ? completo : 'Nuovo autore';
    }

    if (nomeInput) nomeInput.addEventListener('input', aggiornaAnteprima);
    if (cognomeInput) cognomeInput.addEventListener('input', aggiornaAnteprima);
    aggiornaAnteprima();
});
</script>

</main>
<?php require_once __DIR__ . '/parti/piede.php'; ?>