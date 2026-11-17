<?php
/*
 * 02 - EREDITARIETÀ
 * Esecuzione:  php 02-ereditarieta.php
 *
 * Una classe può ESTENDERE un'altra: eredita proprietà e metodi, e può aggiungerne
 * o ridefinirne alcuni.
 */

class Libro
{
    public function __construct(
        public string $titolo,
        public string $autore,
        protected int $pagine,   // protected: visibile nella classe e nelle classi figlie
    ) {
    }

    public function descrizione(): string
    {
        return "{$this->titolo} di {$this->autore}, {$this->pagine} pagine";
    }
}

class Ebook extends Libro
{
    public function __construct(
        string $titolo,
        string $autore,
        int $pagine,
        public string $formato,   // proprietà in più
    ) {
        // parent:: chiama il metodo della classe madre
        parent::__construct($titolo, $autore, $pagine);
    }

    // OVERRIDE: stesso nome del metodo della madre, comportamento diverso
     public function descrizione(): string
    {
        return parent::descrizione() . " (ebook, {$this->formato})";
     }
}

$cartaceo = new Libro("Lessico famigliare", "Natalia Ginzburg", 230);
$ebook = new Ebook("Il nome della rosa", "Umberto Eco", 503, "EPUB");

// Lo stesso metodo, chiamato su oggetti diversi, fa cose diverse (polimorfismo)
$libri=[$cartaceo, $ebook];
foreach ($libri as $l) {
    echo $l->descrizione() . "\n";
}

var_dump($ebook instanceof Libro);   // true: un Ebook è anche un Libro
var_dump($cartaceo instanceof Ebook); //false: un Libro non è un Ebook
