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
            'admin_type' => 1,
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
            'admin_type' => 2,
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

    public function test_admin_1_cannot_access_or_approve_floor_19()
    {
        $admin1 = User::create([
            'name' => 'Admin 1 Test',
            'email' => 'admin1@test.com',
            'role' => 'admin',
            'admin_type' => 1,
            'password' => bcrypt('password'),
        ]);

        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L19',
            'jenis' => 'Ruang Sidang',
            'lantai' => '19',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        $res = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Meeting L19',
            'status' => 'pending',
        ]);

        // Admin 1 attempts to approve floor 19 reservation -> expect 403
        $responseApprove = $this->actingAs($admin1)->post("/admin/reservations/{$res->id}/approve");
        $responseApprove->assertStatus(403);

        // Admin 1 attempts to reserve a floor 19 room directly -> expect 403
        $responseReserve = $this->actingAs($admin1)->post('/admin/rooms/reserve', [
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
            'tujuan' => 'Rapat Admin 1',
        ]);
        $responseReserve->assertStatus(403);
    }

    public function test_admin_2_cannot_access_or_approve_other_floors()
    {
        $admin2 = User::create([
            'name' => 'Admin 2 Test',
            'email' => 'admin2@test.com',
            'role' => 'admin',
            'admin_type' => 2,
            'password' => bcrypt('password'),
        ]);

        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        $res = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Meeting L3',
            'status' => 'pending',
        ]);

        // Admin 2 attempts to approve floor 3 reservation -> expect 403
        $responseApprove = $this->actingAs($admin2)->post("/admin/reservations/{$res->id}/approve");
        $responseApprove->assertStatus(403);

        // Admin 2 attempts to reserve a floor 3 room directly -> expect 403
        $responseReserve = $this->actingAs($admin2)->post('/admin/rooms/reserve', [
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
            'tujuan' => 'Rapat Admin 2',
        ]);
        $responseReserve->assertStatus(403);
    }

    public function test_lecturer_can_make_full_day_multi_day_reservation_successfully()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        // Next Monday is always a weekday, avoiding past time checks and Sundays
        $start = Carbon::parse('next monday');
        $end = $start->copy()->addDays(2); // Monday, Tuesday, Wednesday (3 days)

        $response = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tipe_reservasi' => 'sehari_penuh',
            'tanggal_mulai' => $start->toDateString(),
            'tanggal_selesai' => $end->toDateString(),
            'tujuan' => 'Rapat Multi Hari',
            'keterangan' => 'Keterangan rapat',
        ]);

        $response->assertRedirect('/history');
        
        // Assert 3 reservations are created
        $this->assertDatabaseCount('reservations', 3);
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $this->assertDatabaseHas('reservations', [
                'user_id' => $dosen->id,
                'room_id' => $room->id,
                'tanggal' => $d->toDateString(),
                'jam_mulai' => '07:00',
                'jam_selesai' => '18:30',
                'status' => 'approved',
            ]);
        }
    }

    public function test_lecturer_cannot_make_single_day_reservation_on_sunday()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $sunday = Carbon::parse('next sunday')->toDateString();

        $response = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tipe_reservasi' => 'biasa',
            'tanggal' => $sunday,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Hari Minggu',
        ]);

        $response->assertSessionHasErrors(['tanggal']);
    }

    public function test_lecturer_multi_day_reservation_skips_sundays()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $saturday = Carbon::parse('next saturday');
        $monday = $saturday->copy()->addDays(2); // Saturday, Sunday, Monday (3 days)

        $response = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tipe_reservasi' => 'sehari_penuh',
            'tanggal_mulai' => $saturday->toDateString(),
            'tanggal_selesai' => $monday->toDateString(),
            'tujuan' => 'Rapat Akhir Pekan',
        ]);

        $response->assertRedirect('/history');

        // Assert only Saturday and Monday are created (Sunday skipped)
        $this->assertDatabaseCount('reservations', 2);
        $this->assertDatabaseHas('reservations', [
            'tanggal' => $saturday->toDateString(),
            'jam_mulai' => '07:00',
        ]);
        $this->assertDatabaseHas('reservations', [
            'tanggal' => $monday->toDateString(),
            'jam_mulai' => '07:00',
        ]);
        $this->assertDatabaseMissing('reservations', [
            'tanggal' => $saturday->copy()->addDay()->toDateString(), // Sunday
        ]);
    }

    public function test_lecturer_reservation_limits()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $start = Carbon::parse('next monday');
        $end = $start->copy()->addDays(15); // 16 days

        $response = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tipe_reservasi' => 'sehari_penuh',
            'tanggal_mulai' => $start->toDateString(),
            'tanggal_selesai' => $end->toDateString(),
            'tujuan' => 'Rapat Panjang',
        ]);

        $response->assertSessionHasErrors(['tanggal_selesai']);
    }

    public function test_admin_can_make_full_day_multi_day_reservation_without_limits()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'admin_type' => 1,
            'password' => bcrypt('password'),
        ]);

        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $start = Carbon::parse('next monday');
        $end = $start->copy()->addDays(20); // 21 days range

        $response = $this->actingAs($admin)->post('/admin/rooms/reserve', [
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tipe_reservasi' => 'sehari_penuh',
            'tanggal_mulai' => $start->toDateString(),
            'tanggal_selesai' => $end->toDateString(),
            'tujuan' => 'Rapat Admin Panjang',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // 21 days has 3 Sundays, so 21 - 3 = 18 reservations should be created
        $this->assertDatabaseCount('reservations', 18);
    }

    public function test_lecturer_can_make_overlapping_reservations_if_existing_is_pending_on_floor_19()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L19',
            'jenis' => 'Ruang Sidang',
            'lantai' => '19',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::now()->addDays(2)->toDateString();

        // Create a pending reservation
        Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Pending Meeting',
            'status' => 'pending',
        ]);

        // Make overlapping reservation - should succeed
        $response = $this->actingAs($dosen)->post('/reservation/store', [
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Another Meeting',
        ]);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'status' => 'pending',
        ]);
    }

    public function test_pegawai_can_login_and_make_reservation()
    {
        // 1. Create a pegawai user
        $pegawai = User::create([
            'name' => 'Pegawai Test',
            'email' => 'pegawai@test.com',
            'role' => 'pegawai',
            'nip' => '87654321',
        ]);

        // 2. Try logging in through DosenLoginController (NIP + email login)
        $responseLogin = $this->post('/login', [
            'nip' => '87654321',
            'email' => 'pegawai@test.com',
        ]);
        $responseLogin->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($pegawai);

        // 3. Make reservation
        $room = Room::create([
            'nama' => 'Ruang C',
            'jenis' => 'Ruang Rapat',
            'lantai' => '3',
            'kapasitas' => 15,
            'status' => 'tersedia',
        ]);
        $tanggal = Carbon::tomorrow()->toDateString();

        $responseReserve = $this->actingAs($pegawai)->post('/reservation/store', [
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => 'Rapat Pegawai Bulanan',
        ]);

        $responseReserve->assertRedirect('/history');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $pegawai->id,
            'room_id' => $room->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'status' => 'approved',
        ]);
    }

    public function test_user_can_cancel_own_pending_or_approved_reservation_in_future()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        // 1. Pending reservation
        $resPending = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Pending',
            'status' => 'pending',
        ]);

        // 2. Approved reservation
        $resApproved = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => 'Rapat Approved',
            'status' => 'approved',
        ]);

        // Cancel pending
        $response1 = $this->actingAs($dosen)->post("/reservation/{$resPending->id}/cancel");
        $response1->assertRedirect();
        $response1->assertSessionHas('success');
        $this->assertEquals('cancelled', $resPending->fresh()->status);
        $this->assertEquals('Dibatalkan oleh user', $resPending->fresh()->alasan_pembatalan);

        // Cancel approved
        $response2 = $this->actingAs($dosen)->post("/reservation/{$resApproved->id}/cancel");
        $response2->assertRedirect();
        $response2->assertSessionHas('success');
        $this->assertEquals('cancelled', $resApproved->fresh()->status);
        $this->assertEquals('Dibatalkan oleh user', $resApproved->fresh()->alasan_pembatalan);
    }

    public function test_user_cannot_cancel_other_users_reservation()
    {
        $dosen1 = User::create([
            'name' => 'Dosen 1',
            'email' => 'dosen1@test.com',
            'role' => 'dosen',
            'nip' => '11111111',
        ]);

        $dosen2 = User::create([
            'name' => 'Dosen 2',
            'email' => 'dosen2@test.com',
            'role' => 'dosen',
            'nip' => '22222222',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        $res = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen1->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Dosen 1',
            'status' => 'pending',
        ]);

        // Dosen 2 tries to cancel Dosen 1's reservation
        $response = $this->actingAs($dosen2)->post("/reservation/{$res->id}/cancel");
        $response->assertStatus(403);
        $this->assertEquals('pending', $res->fresh()->status);
    }

    public function test_user_cannot_cancel_past_reservation()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::yesterday()->toDateString();

        $res = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Kemarin',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($dosen)->post("/reservation/{$res->id}/cancel");
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals('approved', $res->fresh()->status);
    }

    public function test_user_cannot_cancel_already_rejected_or_cancelled_reservation()
    {
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'role' => 'dosen',
            'nip' => '12345678',
        ]);

        $room = Room::create([
            'nama' => 'Ruang L3',
            'jenis' => 'Ruang Sidang',
            'lantai' => '3',
            'kapasitas' => 20,
            'status' => 'tersedia',
        ]);

        $tanggal = Carbon::tomorrow()->toDateString();

        $resRejected = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'tujuan' => 'Rapat Ditolak',
            'status' => 'rejected',
        ]);

        $resCancelled = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $dosen->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => 'Rapat Batal',
            'status' => 'cancelled',
        ]);

        // Cancel rejected
        $response1 = $this->actingAs($dosen)->post("/reservation/{$resRejected->id}/cancel");
        $response1->assertRedirect();
        $response1->assertSessionHas('error');
        $this->assertEquals('rejected', $resRejected->fresh()->status);

        // Cancel cancelled
        $response2 = $this->actingAs($dosen)->post("/reservation/{$resCancelled->id}/cancel");
        $response2->assertRedirect();
        $response2->assertSessionHas('error');
        $this->assertEquals('cancelled', $resCancelled->fresh()->status);
    }
}

