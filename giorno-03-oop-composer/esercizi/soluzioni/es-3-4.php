<?php
// Esercizio 3.4 - Ebook e audiolibri
class Libro
{
    public function __construct(
        public string $titolo,
        public string $autore,
        protected int $pagine,
    ) {
    }

    public function descrizione(): string
    {
        return "{$this->titolo} di {$this->autore}, {$this->pagine} pagine";
    }
}

class Ebook extends Libro
{
    public function __construct(string $titolo, string $autore, int $pagine, public string $formato)
    {
        parent::__construct($titolo, $autore, $pagine);
    }

    public function descrizione(): string
    {
        return parent::descrizione() . " (ebook, {$this->formato})";
    }
}

class Audiolibro extends Libro
{
    public function __construct(string $titolo, string $autore, int $pagine, public int $durataMinuti)
    {
        parent::__construct($titolo, $autore, $pagine);
    }

    public function descrizione(): string
    {
        $ore = intdiv($this->durataMinuti, 60);
        $minuti = $this->durataMinuti % 60;
        return parent::descrizione() . " (audiolibro, $ore ore e $minuti minuti)";
    }
}

$libri = [
    new Libro("Lessico famigliare", "Natalia Ginzburg", 230),
    new Ebook("Il nome della rosa", "Umberto Eco", 503, "EPUB"),
    new Audiolibro("Il barone rampante", "Italo Calvino", 270, 500),
];

foreach ($libri as $l) {
    echo $l->descrizione() . "\n";
}
