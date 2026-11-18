<?php
namespace App\Modelli;

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
        return array_sum(array_map(fn($l) => $l->pagine, $this->libri));
    }

    public function disponibili(): array
    {
        return array_values(array_filter($this->libri, fn($l) => $l->disponibile()));
    }
}
