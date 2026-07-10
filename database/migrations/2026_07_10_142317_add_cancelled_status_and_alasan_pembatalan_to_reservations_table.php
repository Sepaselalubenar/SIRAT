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
        // PostgreSQL: drop existing check constraint and re-add with 'cancelled' included
        if (\DB::getDriverName() !== 'sqlite') {
            \DB::statement("ALTER TABLE reservations DROP CONSTRAINT IF EXISTS reservations_status_check");
            \DB::statement("ALTER TABLE reservations ADD CONSTRAINT reservations_status_check CHECK (status IN ('pending','approved','rejected','cancelled'))");
        }

        Schema::table('reservations', function (Blueprint $table) {
            $table->text('alasan_pembatalan')->nullable()->after('alasan_penolakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('alasan_pembatalan');
        });

        // Revert: drop 'cancelled' from allowed status values
        if (\DB::getDriverName() !== 'sqlite') {
            \DB::statement("ALTER TABLE reservations DROP CONSTRAINT IF EXISTS reservations_status_check");
            \DB::statement("ALTER TABLE reservations ADD CONSTRAINT reservations_status_check CHECK (status IN ('pending','approved','rejected'))");
        }
    }
};
