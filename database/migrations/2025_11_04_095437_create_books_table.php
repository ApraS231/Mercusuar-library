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
            $table->string('judul'); 
            $table->string('penulis')->nullable(); 
            $table->string('penerbit')->nullable(); 
            $table->text('deskripsi')->nullable(); 
            $table->string('isbn')->nullable()->unique(); 
            $table->string('gambar_cover')->nullable(); 
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
