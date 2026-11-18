<?php
// Esercizio 3.1 - La classe Autore
class Autore
{
    public function __construct(
        public string $nome,
        public string $cognome,
        public int $annoNascita,
    ) {
    }

    public function nomeCompleto(): string
    {
        return "{$this->nome} {$this->cognome}";
    }

    public function etaOggi(): int
    {
        return (int) date('Y') - $this->annoNascita;
    }
}

$autori = [
    new Autore("Italo", "Calvino", 1923),
    new Autore("Natalia", "Ginzburg", 1916),
    new Autore("Umberto", "Eco", 1932),
];

foreach ($autori as $a) {
    echo "{$a->nomeCompleto()}, nato nel {$a->annoNascita}\n";
}
