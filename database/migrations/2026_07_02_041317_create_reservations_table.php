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
    Schema::create('reservations', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->foreignId('room_id')->constrained()->cascadeOnDelete();

        $table->date('tanggal');

        $table->time('jam_mulai');
        $table->time('jam_selesai');

        $table->string('tujuan'); // contoh: Sidang, Meeting, Ujian Sidang Tugas Akhir

        $table->text('keterangan')->nullable(); // catatan tambahan dari dosen (opsional)

        $table->enum('status', [
            'pending',
            'approved',
            'rejected'
        ])->default('pending');

        $table->text('alasan_penolakan')->nullable(); // diisi admin kalau status rejected

        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
