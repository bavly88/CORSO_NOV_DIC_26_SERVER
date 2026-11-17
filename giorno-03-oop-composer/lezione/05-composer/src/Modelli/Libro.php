<?php
// Il namespace corrisponde alla cartella: src/Modelli -> App\Modelli
namespace App\Modelli;

class Libro
{
    public function __construct(
        public string $titolo,
        public Autore $autore,   // una proprietà può contenere un altro oggetto
        public int $anno,
    ) {
    }

    public function descrizione(): string
    {
        return "{$this->titolo} di {$this->autore->nomeCompleto()} ({$this->anno})";
    }
}
