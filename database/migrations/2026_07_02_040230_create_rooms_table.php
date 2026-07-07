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
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();

        $table->string('nama');
        $table->string('jenis')->nullable(); // contoh: Ruang Sidang, Ruang Meeting
        $table->string('lantai');
        $table->integer('kapasitas');
        $table->json('fasilitas')->nullable(); // contoh: ["TV 55 inch", "Proyektor", "WiFi"]
        $table->text('deskripsi')->nullable();

        $table->enum('status', [
            'tersedia',
            'dipakai',
            'maintenance'
        ])->default('tersedia');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
