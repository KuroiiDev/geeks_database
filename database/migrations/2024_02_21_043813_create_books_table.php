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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->longText('cover')->nullable();
            $table->string('title')->nullable();
            $table->string('writer')->nullable();
            $table->string('publisher')->nullable();
            $table->text('synopsis')->nullable();
            $table->integer('rented')->default(0);
            $table->enum('status', ['AVAILABLE', 'UNAVAILABLE'])->nullable()->default('AVAILABLE');     
            $table->integer('publish_year')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
