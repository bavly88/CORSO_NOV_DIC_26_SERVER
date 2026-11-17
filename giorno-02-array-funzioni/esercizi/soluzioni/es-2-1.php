<?php
// 2.1 - Il registro dei prestiti

$prestiti = [
    'Il nome della rosa'  => 'Rossi',
    'Le città invisibili' => 'Bianchi',
    "L'isola di Arturo"   => 'Rossi',
];

// Passo 1 - nuovo prestito
$prestiti['Il pendolo di Foucault'] = 'Verdi';

// Passo 2 - restituzione
unset($prestiti['Le città invisibili']);

// Passo 3 - stampa del registro
foreach ($prestiti as $titolo => $lettore) {
    echo "$titolo: in prestito a $lettore\n";
}
echo "\n";

// Passo 4 - un libro è in prestito?
$cercato = 'Marcovaldo';
if (isset($prestiti[$cercato])) {
    echo "$cercato: in prestito\n";
} else {
    echo "$cercato: disponibile\n";
}
echo "\n";

// Passo 5 - ordina per titolo (ksort modifica l'array, non lo restituisce)
ksort($prestiti);
foreach ($prestiti as $titolo => $lettore) {
    echo "$titolo: in prestito a $lettore\n";
}
echo "\n";

// Passo 6 - quanti libri ha Rossi?
$perLettore = array_count_values($prestiti);   // ['Rossi' => 2, 'Verdi' => 1]
echo "Rossi ha {$perLettore['Rossi']} libri in prestito\n\n";

// Passo 7 - print_r e var_dump
print_r($prestiti);
var_dump($prestiti);
// var_dump mostra anche il tipo e la lunghezza di ogni valore: string(5) "Rossi"
