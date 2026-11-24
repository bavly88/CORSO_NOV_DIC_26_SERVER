<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            // Utente cancellato: i suoi prestiti spariscono con lui (cascade).
            // Si potrebbe anche scegliere restrict, per non perdere lo storico.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Libro con prestiti: non si può cancellare (restrict), come nel database biblioteca
            $table->foreignId('book_id')->constrained()->restrictOnDelete();
            $table->date('loaned_at');
            $table->date('returned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
