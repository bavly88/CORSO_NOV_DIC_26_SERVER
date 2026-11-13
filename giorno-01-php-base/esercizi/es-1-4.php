<?php

$prestito = strtotime('2026-11-13');

echo "Prestito: " . date('d/m/Y', $prestito) . "\n";

$scadenza = strtotime('+30 days', $prestito);

$giornoNumero = (int)date('N', $scadenza);

$giornoNome = match ($giornoNumero) {
    1 => 'lunedì',
    2 => 'martedì',
    3 => 'mercoledì',
    4 => 'giovedì',
    5 => 'venerdì',
    6 => 'sabato',
    7 => 'domenica',
    default => '',
};

$notaSpostamento = "";
if ($giornoNumero === 7) {
    $scadenza = strtotime('+1 day', $scadenza);
    $giornoNome = 'lunedì';
    $notaSpostamento = " (spostata al lunedì)";
}

$dataScadenzaFormattata = date('d/m/Y', $scadenza);
echo "Scadenza: $dataScadenzaFormattata - $giornoNome$notaSpostamento\n";
?>
