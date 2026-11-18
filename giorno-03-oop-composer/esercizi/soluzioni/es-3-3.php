<?php
// Esercizio 3.3 - La biblioteca
require __DIR__ . '/libro.inc.php';

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
        return array_values(array_filter($this->libri, fn($l) => $l->autore === $autore));
    }

    public function totalePagine(): int
    {
        $totale = 0;
        foreach ($this->libri as $l) {
            $totale += $l->pagine;
        }
        return $totale;
    }

    public function disponibili(): array
    {
        return array_values(array_filter($this->libri, fn($l) => $l->disponibile()));
    }
}

$b = new Biblioteca();
$b->aggiungi(new Libro("Il barone rampante", "Italo Calvino", 270));
$b->aggiungi(new Libro("Le città invisibili", "Italo Calvino", 168));
$b->aggiungi(new Libro("Il nome della rosa", "Umberto Eco", 503, 2));
$b->aggiungi(new Libro("Lessico famigliare", "Natalia Ginzburg", 230));
$b->aggiungi(new Libro("Il giorno della civetta", "Leonardo Sciascia", 130));

$calvino = $b->cercaPerAutore("Italo Calvino");
$calvino[0]->presta();

echo "Libri: {$b->numeroLibri()}\n";
echo "Di Calvino: " . count($calvino) . "\n";
echo "Pagine totali: {$b->totalePagine()}\n";
echo "Disponibili: " . implode(", ", array_map(fn($l) => $l->titolo, $b->disponibili())) . "\n";
