<?php

class Libro
{
    public function __construct(
        public string $titolo,
        public string $autore,
        public int $pagine,
        private int $copie = 1
    ) {
        if (trim($this->titolo) === '') {
            throw new InvalidArgumentException("Il titolo non può essere vuoto");
        }
        if ($this->pagine <= 0) {
            throw new InvalidArgumentException("Il numero di pagine deve essere maggiore di zero");
        }
    }

    public function presta(): void
    {
        if ($this->copie <= 0) {
            throw new RuntimeException("Nessuna copia disponibile");
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


echo "Creazione libro con 0 pagine" . PHP_EOL;
try {
    $libroErrato = new Libro("Il fu Mattia Pascal", "Luigi Pirandello", 0);
} catch (InvalidArgumentException $e) {
    echo "Errore intercettato: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

echo "Prestiti multipli con 1 sola copia" . PHP_EOL;
try {
    $libro = new Libro("1984", "George Orwell", 328, 1);
    echo "Creato: '{$libro->titolo}', copie iniziali: {$libro->copie()}" . PHP_EOL;

    echo "Tentativo di primo prestito..." . PHP_EOL;
    $libro->presta();
    echo "Prestito riuscito. Copie rimanenti: {$libro->copie()}" . PHP_EOL;

    echo "Tentativo di secondo prestito" . PHP_EOL;
    $libro->presta();
} catch (RuntimeException $e) {
    echo "Errore intercettato: " . $e->getMessage() . PHP_EOL;
}
