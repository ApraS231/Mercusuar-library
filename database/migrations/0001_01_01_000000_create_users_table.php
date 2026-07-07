<?php

use App\Enums\Role;
use App\Enums\StatusAkun;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id('Id_pengguna'); 
            $table->string('Nama_Pengguna', 20); 
            $table->string('Email_Pengguna', 20)->unique(); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('Kata_Sandi_Pengguna', 255); 
            $table->string('Peran_Akses_Pengguna', 15)->default(Role::User->value); 
            $table->string('Status_Akun_Pengguna', 12)->default(StatusAkun::Aktif->value); 
            $table->text('Alamat_Pengguna')->nullable(); 
            $table->string('No_Telepon_Pengguna', 12)->nullable(); 
            $table->rememberToken(); 
            $table->timestamps(); 
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
