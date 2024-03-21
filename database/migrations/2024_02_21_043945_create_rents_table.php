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
        Schema::create('rents', function (Blueprint $table) {
            $table->id('rent_id');
            $table->foreignId('user_id');
            $table->foreignId('book_id');
            $table->dateTime('rent_date')->nullable();
            $table->dateTime('return_date');
            $table->enum('status', ['RENTED', 'BOOKED', 'LATE', 'RETURNED'])->nullable()->default('BOOKED');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('book_id')->references('book_id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
