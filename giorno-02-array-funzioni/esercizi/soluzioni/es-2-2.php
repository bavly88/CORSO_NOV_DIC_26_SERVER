<?php
// 2.2 - Funzioni per le stringhe
// Passo 6: decommentare la riga seguente (deve essere la prima istruzione del file)
// declare(strict_types=1);

// Passo 1
function iniziali(string $autore): string
{
    $risultato = '';
    foreach (explode(' ', $autore) as $parola) {
        $risultato .= $parola[0] . '.';
    }
    return $risultato;
}

// Passo 2
function stelle(int $voto, int $massimo = 5): string
{
    return str_repeat('★', $voto) . str_repeat('☆', $massimo - $voto);
}

// Passo 3
function euro(float $importo): string
{
    return number_format($importo, 2, ',', '.') . ' €';
}

// Passo 4
function slug(string $titolo): string
{
    $testo = strtolower($titolo);
    $testo = strtr($testo, ['à' => 'a', 'è' => 'e', 'é' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u']);
    $testo = str_replace([' ', "'"], '-', $testo);
    return $testo;
}

echo iniziali("Italo Calvino") . "\n";        // I.C.
echo iniziali("Umberto Eco") . "\n";          // U.E.
echo stelle(3) . "\n";                        // ★★★☆☆
echo stelle(7, 10) . "\n";                    // ★★★★★★★☆☆☆
echo euro(1234.5) . "\n";                     // 1.234,50 €
echo euro(8) . "\n";                          // 8,00 €
echo slug("Se questo è un uomo") . "\n";      // se-questo-e-un-uomo
echo slug("L'isola di Arturo") . "\n";        // l-isola-di-arturo
echo "\n";

// Passo 5
$libri = require __DIR__ . '/libri.php';
foreach ($libri as $libro) {
    echo iniziali($libro['autore']) . ' - ' . $libro['titolo'] . ' - ' . slug($libro['titolo']) . "\n";
}
// Fruttero e Lucentini -> F.e.L. : per avere F.L. si saltano le parole con strlen($parola) <= 1

// Passo 6 - provare una riga alla volta
// echo stelle("3") . "\n";    senza strict: ★★★☆☆ (la stringa "3" viene convertita) - con strict: TypeError
// echo stelle(3.7) . "\n";    senza strict: ★★★☆☆ con avviso Deprecated (perde il ,7) - con strict: TypeError
// echo stelle("tre") . "\n";  TypeError in entrambi i casi: "tre" non è un numero
