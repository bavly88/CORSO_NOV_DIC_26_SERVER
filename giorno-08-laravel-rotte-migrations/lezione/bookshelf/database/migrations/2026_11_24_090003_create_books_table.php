<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->index();
            $table->string('isbn', 13)->nullable()->unique();
            $table->smallInteger('year')->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->string('cover_path')->nullable();

            // Relazione 1-N con genres: equivale a
            // genre_id BIGINT UNSIGNED NULL + FOREIGN KEY ... REFERENCES genres(id) ON DELETE SET NULL
            $table->foreignId('genre_id')->nullable()->constrained()->nullOnDelete();

            // Chi ha inserito il libro (tabella users creata dalle migration di Laravel)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
