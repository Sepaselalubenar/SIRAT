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
        Schema::table('users', function (Blueprint $table) {
            // Dosen login pakai NIP + email (tanpa password), admin login pakai email + password.
            $table->enum('role', ['dosen', 'admin'])->default('dosen')->after('email');
            $table->string('nip')->nullable()->unique()->after('role');

            // Dosen tidak perlu password (akun dibuat dari database dosen), jadi nullable.
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nip']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
