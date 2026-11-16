<?php

$prestiti = [
    'Il nome della rosa'   => 'Rossi',
    'Le città invisibili' => 'Bianchi',
    "L'isola di Arturo"   => 'Rossi',
];


$prestiti['Il pendolo di Foucault'] = 'Verdi';

unset($prestiti['Le città invisibili']);


echo "--- Registro dei prestiti --- \n";
foreach ($prestiti as $titolo => $lettore) {
    echo "$titolo: in prestito a $lettore\n";
}
echo "\n";


echo "--- Verifica disponibilità --- \n";
if (isset($prestiti['Marcovaldo'])) {
    echo "Marcovaldo: in prestito\n";
} else {
    echo "Marcovaldo: disponibile\n";
}

if (isset($prestiti['Il nome della rosa'])) {
    echo "Il nome della rosa: in prestito\n";
} else {
    echo "Il nome della rosa: disponibile\n";
}
echo "\n";


echo "--- Registro ordinato per titolo --- \n";
ksort($prestiti);
foreach ($prestiti as $titolo => $lettore) {
    echo "$titolo: in prestito a $lettore\n";
}
echo "\n";


echo "--- Conteggio libri per lettore --- \n";
$conteggi = array_count_values($prestiti);

$libri_rossi = isset($conteggi['Rossi']) ? $conteggi['Rossi'] : 0;
echo "Rossi ha $libri_rossi libri in prestito\n";

echo "--- print_r --- \n";
print_r($prestiti);
echo "\n";

echo "--- var_dump --- \n";
var_dump($prestiti);

/*
 * Risposta Passo 7:
 * Quale informazione in più mostra var_dump?
 * var_dump mostra informazioni dettagliate sul tipo di dato e sulla struttura:
 * - Il tipo di ogni variabile/elemento (es. string)
 * - La lunghezza in byte delle stringhe (es. string(5) indica una stringa di 5 caratteri)
 * - Il tipo e la dimensione delle chiavi e dei valori nell'array.
 * print_r, invece, mostra una rappresentazione più pulita e leggibile, incentrata 
 * solo sui valori e sulle chiavi, senza i dettagli tecnici sui tipi di dati.
 */