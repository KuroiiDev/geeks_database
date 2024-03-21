<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genres_relations', function (Blueprint $table) {
            $table->id('relation_id');
            $table->foreignId('genre_id');
            $table->foreignId('book_id');
            $table->foreign('genre_id')->references('genre_id')->on('genres');
            $table->foreign('book_id')->references('book_id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres_relations');
    }
};
