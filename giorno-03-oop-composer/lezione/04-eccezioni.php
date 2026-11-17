<?php
/*
 * 04 - ECCEZIONI
 * Esecuzione:  php 04-eccezioni.php
 *
 * Un'ECCEZIONE segnala un errore: throw la lancia, try / catch la gestisce.
 * Se nessuno la gestisce, lo script si ferma con "Fatal error: Uncaught ...".
 */

class Libro
{
    public function __construct(public string $titolo, public int $pagine)
    {
        // il costruttore rifiuta dati senza senso
        if (trim($titolo) === '') {
            throw new InvalidArgumentException("Il titolo è obbligatorio");
        }
        if ($pagine <= 0) {
            throw new InvalidArgumentException("Le pagine devono essere più di zero");
        }
    }
}

try {
    $buono = new Libro("Marcovaldo", 160);
    echo "Creato: {$buono->titolo}\n";
    $sbagliato = new Libro("Libro vuoto", 0);   // qui parte l'eccezione...
    echo "Questa riga non viene eseguita\n";      // ...e si salta al catch
} catch (InvalidArgumentException $e) {
    echo "Errore: " . $e->getMessage() . "\n";
}
finally{
    echo "questo viene sempre eseguito\n";//si usa per chiudere connessioni aperte o resettare qualcosa che altrimenti rimarrebbe in uno stato non adatto
}

echo "Lo script continua\n";

// Prova: togli try e catch e guarda cosa succede.
