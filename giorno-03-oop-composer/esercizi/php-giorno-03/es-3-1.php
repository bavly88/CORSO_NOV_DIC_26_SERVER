<?php

class Autore
{
    public function __construct(
        public string $nome,
        public string $cognome,
        public int $annoNascita
    ) {}

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
    new Autore('Italo', 'Calvino', 1923),
    new Autore('Umberto', 'Eco', 1932),
    new Autore('Elena', 'Ferrante', 1943),
];

foreach ($autori as $autore) {
    echo "{$autore->nomeCompleto()}, nato nel {$autore->annoNascita}" . PHP_EOL;
}