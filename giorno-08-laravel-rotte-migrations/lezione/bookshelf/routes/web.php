<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 2.1 rotte con closure
Route::get('/ciao', fn () => 'Ciao dal corso!');

// 2.2 e 2.3 parametri e vista
Route::get('/ciao/{nome}', function (string $nome) {
    return view('saluto', ['nome' => $nome]);
});

Route::get('/pagina/{numero?}', fn (int $numero = 1) => "Pagina $numero");

Route::get('/libri/{id}', fn (int $id) => "Libro $id")
    ->whereNumber('id');

// 2.4 rotte con nome
Route::get('/info', fn () => 'Bookshelf - corso Full Stack')
    ->name('info');
