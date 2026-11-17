<?php

// ESPERIMENTO STEP 5 (Risposta):
// Se rinominiamo "Libro.php" in "libro.php" (tutto minuscole), su Windows lo script potrebbe 
// continuare a funzionare perché il file system di Windows non fa distinzione tra maiuscole 
// e minuscole (case-insensitive). 
// Tuttavia, su un server di produzione Linux (che è case-sensitive), l'autoloader PSR-4 di Composer 
// fallirebbe miseramente generando un errore "Class not found", poiché la convenzione PSR-4 
// richiede che il nome del file corrisponda esattamente al nome della classe.

require __DIR__ . '/vendor/autoload.php';

use App\Modelli\Libro;
use App\Modelli\Biblioteca;

echo "Test della Biblioteca con Composer" . PHP_EOL;

$biblioteca = new Biblioteca();

$l1 = new Libro("1984", "George Orwell", 328, 2);
$l2 = new Libro("La fattoria degli animali", "George Orwell", 140, 1);
$l3 = new Libro("Il fu Mattia Pascal", "Luigi Pirandello", 238, 1);

$biblioteca->aggiungi($l1);
$biblioteca->aggiungi($l2);
$biblioteca->aggiungi($l3);

echo "Numero totale di libri: {$biblioteca->numeroLibri()}" . PHP_EOL;
echo "Pagine totali: {$biblioteca->totalePagine()}" . PHP_EOL;

echo "Libri di George Orwell:" . PHP_EOL;
foreach ($biblioteca->cercaPerAutore("George Orwell") as $libro) {
    echo "- {$libro->titolo}" . PHP_EOL;
}
