<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabella pivot per la relazione N-N tra autori e libri.
 * Convenzione Laravel: nomi dei due modelli al singolare, in ordine alfabetico,
 * separati da underscore -> author_book.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('author_book', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->primary(['author_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
