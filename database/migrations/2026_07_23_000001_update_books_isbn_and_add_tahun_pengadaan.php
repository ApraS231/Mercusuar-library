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
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'tahun_pengadaan')) {
                $table->integer('tahun_pengadaan')->nullable()->after('ISBN');
            }
            $table->string('ISBN', 28)->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('No_Telepon_Pengguna', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'tahun_pengadaan')) {
                $table->dropColumn('tahun_pengadaan');
            }
            $table->string('ISBN', 8)->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('No_Telepon_Pengguna', 12)->nullable()->change();
        });
    }
};
