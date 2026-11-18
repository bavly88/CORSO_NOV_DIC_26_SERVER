<?php
// Esercizio 3.5 - Esecuzione: composer dump-autoload, poi php esegui.php
require __DIR__ . '/vendor/autoload.php';

use App\Modelli\Biblioteca;
use App\Modelli\Libro;

$b = new Biblioteca();
$b->aggiungi(new Libro("Il barone rampante", "Italo Calvino", 270));
$b->aggiungi(new Libro("Il nome della rosa", "Umberto Eco", 503));
$b->cercaPerAutore("Umberto Eco")[0]->presta();

echo "Libri: {$b->numeroLibri()}, pagine: {$b->totalePagine()}, disponibili: " . count($b->disponibili()) . "\n";

// Note: dentro un namespace le classi di PHP (InvalidArgumentException, RuntimeException)
// si scrivono con la barra iniziale: \InvalidArgumentException.
// Con il file libro.php (minuscolo) su Windows funziona, perché Windows non distingue
// maiuscole e minuscole; su un server Linux l'autoload non lo trova: "Class not found".
