<?php

use App\Enums\StatusPeminjaman;
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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('Id_Peminjaman'); 
            $table->foreignId('Id_Pengguna')->constrained('users', 'Id_pengguna')->onDelete('restrict');
            $table->foreignId('Id_Buku')->constrained('books', 'Id_Buku')->onDelete('restrict');
            
            $table->string('Status_Peminjaman')->default('Pinjam'); // uses string or enum as string
            $table->dateTime('Tanggal_Pinjam');
            $table->dateTime('Tanggal_Disetujui')->nullable();
            $table->date('Tanggal_Jatuh_Tempo')->nullable();
            $table->dateTime('Tanggal_Selesai')->nullable();
            
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
