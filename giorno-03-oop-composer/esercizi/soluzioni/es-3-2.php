<?php
// Esercizio 3.2 - Un Libro che si difende
// (la classe è in libro.inc.php, per riusarla nel 3.3; si può anche copiarla qui)
require __DIR__ . '/libro.inc.php';

try {
    $vuoto = new Libro("Libro vuoto", "Nessuno", 0);
} catch (InvalidArgumentException $e) {
    echo "Errore: {$e->getMessage()}\n";
}

$libro = new Libro("Il barone rampante", "Italo Calvino", 270);
try {
    $libro->presta();
    echo "Primo prestito ok, copie: {$libro->copie()}\n";
    $libro->presta();
} catch (RuntimeException $e) {
    echo "Errore: {$e->getMessage()}\n";
}

echo "Fine dello script\n";
