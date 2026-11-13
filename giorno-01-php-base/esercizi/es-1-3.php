<?php
$genere = "Giallo";

$scaffale = match ($genere) {
    'Romanzo'            => 'A',
    'Giallo', 'Thriller' => 'B',
    'Saggio'             => 'C',
    'Poesia'             => 'D',
    default              => 'Chiedi al bancone',
};

if ($scaffale === 'Chiedi al bancone') {
    echo "Il genere $genere non ha uno scaffale assegnato: $scaffale.\n";
} else {
    echo "Il genere $genere è nello scaffale $scaffale.\n";
}
?>