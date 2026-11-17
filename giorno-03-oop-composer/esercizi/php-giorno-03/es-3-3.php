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

class Biblioteca
{
    private array $libri = [];

    public function aggiungi(Libro $libro): void
    {
        $this->libri[] = $libro;
    }

    public function numeroLibri(): int
    {
        return count($this->libri);
    }

    public function cercaPerAutore(string $autore): array
    {
        return array_filter($this->libri, function (Libro $libro) use ($autore) {
            return strcasecmp($libro->autore, $autore) === 0;
        });
    }

    public function totalePagine(): int
    {
        $totale = 0;
        foreach ($this->libri as $libro) {
            $totale += $libro->pagine;
        }
        return $totale;
    }

    public function disponibili(): array
    {
        return array_filter($this->libri, function (Libro $libro) {
            return $libro->disponibile();
        });
    }
}


echo "Creazione della Biblioteca e aggiunta dei libri" . PHP_EOL;
$biblioteca = new Biblioteca();

$l1 = new Libro("1984", "George Orwell", 328, 2);
$l2 = new Libro("La fattoria degli animali", "George Orwell", 140, 1);
$l3 = new Libro("Il fu Mattia Pascal", "Luigi Pirandello", 238, 1);
$l4 = new Libro("Uno, nessuno e centomila", "Luigi Pirandello", 186, 0);
$l5 = new Libro("Il nome della rosa", "Umberto Eco", 503, 3);

$biblioteca->aggiungi($l1);
$biblioteca->aggiungi($l2);
$biblioteca->aggiungi($l3);
$biblioteca->aggiungi($l4);
$biblioteca->aggiungi($l5);

echo "Numero totale di libri in biblioteca: {$biblioteca->numeroLibri()}" . PHP_EOL;
echo "Somma totale delle pagine: {$biblioteca->totalePagine()}" . PHP_EOL;

echo PHP_EOL;
echo "Test dei prestiti" . PHP_EOL;
echo "Copie iniziali di '1984': {$l1->copie()}" . PHP_EOL;
$l1->presta();
echo "Dopo un prestito, copie di '1984': {$l1->copie()}" . PHP_EOL;

echo PHP_EOL;
echo "Ricerca per autore" . PHP_EOL;
$libriOrwell = $biblioteca->cercaPerAutore("George Orwell");
foreach ($libriOrwell as $libro) {
    echo "- {$libro->titolo} ({$libro->autore})" . PHP_EOL;
}

echo PHP_EOL;
echo "Elenco libri attualmente disponibili" . PHP_EOL;
$libriDisponibili = $biblioteca->disponibili();
foreach ($libriDisponibili as $libro) {
    echo "- {$libro->titolo} [Copie: {$libro->copie()}]" . PHP_EOL;
}
