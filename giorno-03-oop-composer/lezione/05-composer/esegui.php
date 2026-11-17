<?php
/*
 * Esecuzione (dalla cartella 05-composer):
 *   composer dump-autoload
 *   php esegui.php
 */
require __DIR__ . '/vendor/autoload.php';   // l'unico require: il resto delle classi lo carica Composer

// use: per scrivere Libro invece di App\Modelli\Libro
use App\Modelli\Autore;
use App\Modelli\Libro;

$calvino = new Autore("Italo", "Calvino");
$libro = new Libro("Il barone rampante", $calvino, 1957);

echo $libro->descrizione() . "\n";
