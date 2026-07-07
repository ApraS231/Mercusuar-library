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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users', 'Id_pengguna')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books', 'Id_Buku')->onDelete('cascade');
            
            $table->tinyInteger('rating'); 
            $table->text('komentar')->nullable(); 
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
