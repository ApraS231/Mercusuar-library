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
            $table->id('Id_Buku'); 
            $table->string('judul', 50); 
            $table->string('penulis', 30)->nullable(); 
            $table->string('penerbit', 20)->nullable(); 
            $table->text('deskripsi')->nullable(); 
            $table->string('ISBN', 8)->nullable()->unique(); 
            $table->string('gambar_cover', 20)->nullable(); 
            $table->integer('stok_total')->default(1); 
            $table->integer('stok_tersedia')->default(1); 
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
