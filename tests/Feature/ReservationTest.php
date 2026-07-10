<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecturer_can_make_multiple_overlapping_reservations_in_different_rooms()
    {
        // 1. Create a lecturer user
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        // 2. Create two rooms on floor 3 (which does not require H+2 restriction/admin approval)
        $roomA = Room::create([
            'nama' => 'Ruang A',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'fasilitas' => ['AC', 'WiFi'],
            'deskripsi' => 'Ruang Sidang A',
            'status' => 'tersedia',
        ]);

        $roomB = Room::create([
            'nama' => 'Ruang B',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'fasilitas' => ['AC', 'WiFi'],
            'deskripsi' => 'Ruang Sidang B',
            'status' => 'tersedia',
        ]);

        // Define a tomorrow date to avoid past time checks
        $tanggal = Carbon::tomorrow()->toDateString();

        // 3. Make reservation 1 for Room A
        $response1 = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $roomA->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Sidang Skripsi Kelompok 1',
            'keterangan' => 'Butuh proyektor',
        ]);

        $response1->assertRedirect('/history');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $dosen->id,
            'room_id' => $roomA->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
        ]);

        // 4. Make reservation 2 for Room B at an overlapping time (09:00 - 11:00)
        // With Opsi A, this must succeed!
        $response2 = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $roomB->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Sidang Skripsi Kelompok 2',
            'keterangan' => 'Sidang paralel',
        ]);

        $response2->assertRedirect('/history');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $dosen->id,
            'room_id' => $roomB->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
        ]);
    }

    public function test_lecturer_cannot_make_overlapping_reservations_in_the_same_room()
    {
        // 1. Create a lecturer user
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        // 2. Create room
        $room = Room::create([
            'nama' => 'Ruang A',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'fasilitas' => ['AC', 'WiFi'],
            'deskripsi' => 'Ruang Sidang A',
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        // 3. Make reservation 1
        $response1 = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Sidang Skripsi Kelompok 1',
        ]);

        $response1->assertRedirect('/history');

        // 4. Make reservation 2 at overlapping time in the same room (must fail validation)
        $response2 = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Sidang Skripsi Kelompok 2',
        ]);

        $response2->assertSessionHasErrors(['jam_mulai']);
    }

    public function test_lecturer_reservation_floor_19_overrides_keterangan_and_requires_tujuan()
    {
        // 1. Create a lecturer user
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        // 2. Create room on Floor 19 (requires approval and has conditional form behavior)
        $room19 = Room::create([
            'nama' => 'Ruang Meeting 19.07',
            'jenis' => 'Ruang Meeting',
            'lantai' => '19',
            'kapasitas' => 10,
            'fasilitas' => ['AC', 'WiFi'],
            'deskripsi' => 'Ruang Meeting 19',
            'status' => 'tersedia',
        ]);

        // Floor 19 requires reservation starting at H+2
        $tanggal = Carbon::now()->addDays(2)->toDateString();

        // 3. Make reservation with a custom text tujuan, and verify it successfully works,
        // and that any provided keterangan is discarded (set to null)
        $response = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room19->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Koordinasi Anggaran Proyek Mandiri 2026',
            'keterangan' => 'Ini keterangan yang harusnya di-override jadi null oleh sistem',
        ]);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $dosen->id,
            'room_id' => $room19->id,
            'tanggal' => $tanggal,
            'tujuan' => 'Rapat Koordinasi Anggaran Proyek Mandiri 2026',
            'keterangan' => null, // must be null
            'status' => 'pending', // Floor 19 defaults to pending
        ]);

        // 4. Try making a reservation without tujuan (must fail validation)
        $responseFail = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room19->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => '', // empty
        ]);

        $responseFail->assertSessionHasErrors(['tujuan']);
    }

    public function test_admin_cannot_make_overlapping_reservations_in_the_same_room()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang A',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'fasilitas' => ['AC', 'WiFi'],
            'deskripsi' => 'Ruang Sidang A',
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        // 1. Create a reservation for Room A
        $response1 = $this->actingAs($admin)->post('/admin/rooms/reserve', [
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Admin',
        ]);

        $response1->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
        ]);

        // 2. Try to create overlapping reservation
        $response2 = $this->actingAs($admin)->post('/admin/rooms/reserve', [
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Rapat Admin 2',
        ]);

        $response2->assertSessionHasErrors(['jam_mulai']);
    }

    public function test_admin_cannot_approve_overlapping_reservation()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang A',
            'jenis' => 'Ruang Sidang',
            'lantai' => '19',
            'kapasitas' => 20,
            'fasilitas' => ['AC', 'WiFi'],
            'deskripsi' => 'Ruang Sidang A',
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        // 1. Create an approved reservation
        $approvedRes = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Approved Meeting',
            'status' => 'approved',
        ]);

        // 2. Force create a pending reservation that overlaps
        $pendingRes = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Overlapping Meeting',
            'status' => 'pending',
        ]);

        // 3. Admin attempts to approve the overlapping pending reservation
        $response = $this->actingAs($admin)->post("/admin/reservations/{$pendingRes->id}/approve");

        // Should redirect back with error in session
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        // Assert that the status is still pending
        $this->assertEquals('pending', $pendingRes->fresh()->status);
    }
}
