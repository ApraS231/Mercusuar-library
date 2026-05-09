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
            $table->id(); // [cite: 78]
            $table->string('name'); // [cite: 79]
            $table->string('email')->unique(); // [cite: 80]
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // [cite: 81]
            $table->string('role')->default(Role::User->value); // [cite: 82]
            $table->string('status_akun')->default(StatusAkun::Aktif->value); // [cite: 83]
            $table->text('alamat')->nullable(); // [cite: 84]
            $table->string('no_telepon')->nullable(); // [cite: 85]
            $table->rememberToken(); // [cite: 86]
            $table->timestamps(); // [cite: 86]
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
