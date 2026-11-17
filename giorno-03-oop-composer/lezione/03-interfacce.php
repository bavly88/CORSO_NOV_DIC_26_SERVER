<?php
/*
 * 03 - INTERFACCE
 * Esecuzione:  php 03-interfacce.php
 *
 * Un'INTERFACCIA è un elenco di metodi che una classe promette di avere.
 * Non contiene codice: dice COSA si può fare, non COME.
 * Laravel ne usa molte: si incontrano leggendo la sua documentazione.
 */

interface Prestabile
{
    public function presta(string $utente): void;
    public function inPrestito(): bool;
}

class Libro implements Prestabile
{
    private ?string $prestatoA = null;   // ?string = stringa oppure null

    public function __construct(public string $titolo)
    {
    }

    public function presta(string $utente): void
    {
        $this->prestatoA = $utente;
    }

    public function inPrestito(): bool
    {
        return $this->prestatoA !== null;
    }
}

class Dvd implements Prestabile
{
    private bool $fuori = false;

    public function __construct(public string $titolo)
    {
    }

    public function presta(string $utente): void
    {
        $this->fuori = true;
    }

    public function inPrestito(): bool
    {
        return $this->fuori;
    }
}

// La funzione accetta QUALSIASI oggetto Prestabile: non le interessa se è un libro o un DVD
function registraPrestito(Prestabile $oggetto, string $utente): void
{
    $oggetto->presta($utente);
    echo "{$oggetto->titolo} prestato a $utente\n";
}

registraPrestito(new Libro("Il barone rampante"), "Anna");
registraPrestito(new Dvd("La vita è bella"), "Marco");
