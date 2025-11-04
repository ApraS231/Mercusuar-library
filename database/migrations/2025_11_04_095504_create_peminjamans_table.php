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
            $table->id(); 
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('book_id')->constrained('books')->onDelete('restrict');
            
            $table->string('status')->default(StatusPeminjaman::Pending->value); 
            $table->text('alamat_pengantaran'); 
            $table->dateTime('jadwal_pengantaran_usulan')->nullable();
            $table->dateTime('jadwal_pengantaran_disetujui')->nullable();
            $table->dateTime('tgl_booking');
            $table->dateTime('tgl_diterima')->nullable();
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->dateTime('tgl_dikembalikan')->nullable();
            
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
