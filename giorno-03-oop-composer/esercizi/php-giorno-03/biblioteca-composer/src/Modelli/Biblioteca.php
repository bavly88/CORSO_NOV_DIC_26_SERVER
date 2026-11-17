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