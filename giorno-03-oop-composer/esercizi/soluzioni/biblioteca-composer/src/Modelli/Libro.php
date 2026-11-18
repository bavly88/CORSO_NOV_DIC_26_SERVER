<?php
namespace App\Modelli;

class Libro
{
    public function __construct(
        public string $titolo,
        public string $autore,
        public int $pagine,
        private int $copie = 1,
    ) {
        if (trim($titolo) === '') {
            throw new \InvalidArgumentException("Il titolo è obbligatorio");
        }
        if ($pagine <= 0) {
            throw new \InvalidArgumentException("Le pagine devono essere più di zero");
        }
    }

    public function presta(): void
    {
        if ($this->copie === 0) {
            throw new \RuntimeException("Nessuna copia disponibile di {$this->titolo}");
        }
        $this->copie--;
    }

    public function restituisci(): void
    {
        $this->copie++;
    }

    public function disponibile(): bool
    {
        return $this->copie > 0;
    }

    public function copie(): int
    {
        return $this->copie;
    }
}
