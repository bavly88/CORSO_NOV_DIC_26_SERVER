<?php

class Libro
{
    public function __construct(
        public string $titolo,
        public string $autore,
        public int $pagine
    ) {}

    public function descrizione(): string
    {
        return "{$this->titolo} di {$this->autore} ({$this->pagine} pagine)";
    }
}

class Ebook extends Libro
{
    public function __construct(
        string $titolo,
        string $autore,
        int $pagine,
        public int $megabyte
    ) {
        parent::__construct($titolo, $autore, $pagine);
    }

    public function descrizione(): string
    {
        return parent::descrizione() . " [Ebook, {$this->megabyte} MB]";
    }
}

class Audiolibro extends Libro
{
    public function __construct(
        string $titolo,
        string $autore,
        int $pagine,
        public int $durataMinuti
    ) {
        parent::__construct($titolo, $autore, $pagine);
    }

    public function descrizione(): string
    {
        $ore = intdiv($this->durataMinuti, 60);
        $minuti = $this->durataMinuti % 60;

        return parent::descrizione() . " (audiolibro, {$ore} ore e {$minuti} minuti)";
    }
}



$elementi = [
    new Libro("I Promessi Sposi", "Alessandro Manzoni", 672),
    new Ebook("Il Signore degli Anelli", "J.R.R. Tolkien", 1200, 15),
    new Audiolibro("Odissea", "Omero", 400, 500), 
];

echo "Catalogo misto" . PHP_EOL;

foreach ($elementi as $elemento) {
    
    echo $elemento->descrizione() . PHP_EOL;
}