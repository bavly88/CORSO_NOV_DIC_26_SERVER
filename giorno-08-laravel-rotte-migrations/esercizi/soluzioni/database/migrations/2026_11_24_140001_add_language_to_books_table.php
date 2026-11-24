<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('language', 30)->nullable()->after('pages');
        });
    }

    // down() annulla up(): migrate:rollback la esegue e la colonna sparisce
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};
