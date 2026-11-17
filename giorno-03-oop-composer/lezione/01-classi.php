<?php
/*
 * 01 - CLASSI E OGGETTI
 * Esecuzione:  php 01-classi.php
 *
 * Una CLASSE è uno stampo: descrive quali dati (proprietà) e quali azioni (metodi)
 * ha un oggetto. Un OGGETTO è un esemplare costruito con new.
 */

class Libro
{
    // COSTRUTTORE: viene chiamato da new. Con "public string $titolo" nei parametri
    // PHP crea la proprietà e la assegna in un colpo solo (constructor promotion).
    public function __construct(
        public string $titolo,
        public string $autore,
        public int $anno,
        private int $copie = 1,   // private: visibile solo dentro la classe
    ) {
    }

    // METODO: una funzione della classe. $this è l'oggetto su cui viene chiamato.
    public function descrizione(): string
    {
        return "{$this->titolo} di {$this->autore} ({$this->anno})";
    }

    public function eta(): int
    {
        return (int) date('Y') - $this->anno;
    }

    // Le proprietà private si leggono e modificano solo attraverso metodi:
    // così la classe controlla che i valori abbiano senso.
    public function copie(): int
    {
        return $this->copie;
    }

    public function presta(): void
    {
        if ($this->copie > 0) {
            $this->copie--;
        }
    }

    public function disponibile(): bool
    {
        return $this->copie > 0;
    }
}

// new crea l'oggetto; -> accede a proprietà e metodi (in JavaScript era il punto)
$libro = new Libro("Il barone rampante", "Italo Calvino", 1957, 2);
$libro->titolo = "nuovo titolo"; #posso modificarla perchè è stata dichiarata public
echo $libro->titolo . "\n";
echo $libro->descrizione() . "\n";
echo "Ha {$libro->eta()} anni\n";

var_dump($libro->disponibile()); //parte con 2 copie
$libro->presta();
var_dump($libro->disponibile()); //rimane una copia
$libro->presta();
var_dump($libro->disponibile());   // false non ci sono più copie

// echo $libro->copie;   // Errore: Cannot access private property

// Ogni oggetto ha i suoi valori
$altro = new Libro("Il nome della rosa", "Umberto Eco", 1980);
echo $altro->descrizione() . " - copie: {$altro->copie()}\n";
