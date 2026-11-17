<?php
namespace App\Modelli;

class Autore
{
    public function __construct(public string $nome, public string $cognome)
    {
    }

    public function nomeCompleto(): string
    {
        return "{$this->nome} {$this->cognome}";
    }
}
