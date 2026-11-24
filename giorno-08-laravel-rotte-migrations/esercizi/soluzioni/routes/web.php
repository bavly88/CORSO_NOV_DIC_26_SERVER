<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ciao/{nome}', function (string $nome) {
    return view('saluto', ['nome' => $nome]);
});

// 8.2
Route::get('/anno/{anno}', fn (int $anno) => "Libri del $anno")
    ->whereNumber('anno')
    ->name('anno');

Route::get('/cerca/{testo?}', fn (?string $testo = null) =>
    $testo ? "Hai cercato: $testo" : 'Scrivi qualcosa da cercare');

// 8.3
Route::get('/info', fn () => view('info'))->name('info');

Route::get('/libro-del-giorno', function () {
    return view('libro', [
        'titolo' => 'Il barone rampante',
        'autore' => 'Italo Calvino',
        'anno' => 1957,
    ]);
});
