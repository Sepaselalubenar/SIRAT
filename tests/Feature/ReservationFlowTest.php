<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $dosen;
    private Room $roomLantai10;
    private Room $roomLantai19;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin
        $this->admin = User::create([
            'name' => 'Admin TULT',
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Buat dosen
        $this->dosen = User::create([
            'name' => 'Dr. Ahmad Fauzi',
            'email' => 'ahmad.fauzi@telkomuniversity.ac.id',
            'role' => 'dosen',
            'nip' => '123456',
        ]);

        // Buat ruangan lantai 10 (auto-approve)
        $this->roomLantai10 = Room::create([
            'nama' => 'Ruang Rapat 1001',
            'jenis' => 'Rapat',
            'lantai' => 10,
            'kapasitas' => 20,
            'status' => 'tersedia',
            'fasilitas' => ['AC', 'Proyektor'],
            'deskripsi' => 'Ruangan rapat lantai 10',
        ]);

        // Buat ruangan lantai 19 (butuh admin approval & H+2)
        $this->roomLantai19 = Room::create([
            'nama' => 'Aula 1901',
            'jenis' => 'Aula',
            'lantai' => 19,
            'kapasitas' => 100,
            'status' => 'tersedia',
            'fasilitas' => ['Sound System', 'AC'],
            'deskripsi' => 'Aula lantai 19',
        ]);
    }

    /**
     * Test Login Dosen dengan NIP + Email yang valid
     */
    public function test_dosen_can_login_with_valid_nip_and_email(): void
    {
        $response = $this->post('/login', [
            'nip' => '123456',
            'email' => 'ahmad.fauzi@telkomuniversity.ac.id',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->dosen);
    }

    /**
     * Test Login Dosen gagal jika NIP atau Email salah
     */
    public function test_dosen_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'nip' => '123456',
            'email' => 'salah@telkomuniversity.ac.id',
        ]);

        $response->assertSessionHasErrors('nip');
        $this->assertGuest();
    }

    /**
     * Test Login Admin dengan Email + Password
     */
    public function test_admin_can_login_with_valid_email_and_password(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Test reservasi otomatis disetujui untuk ruangan non-lantai 19
     */
    public function test_dosen_can_create_reservation_on_non_approval_floor_and_gets_auto_approved(): void
    {
        $this->actingAs($this->dosen);

        $tanggalBesok = now()->addDay()->format('Y-m-d');

        $response = $this->post('/reservation/store', [
            'room_id' => $this->roomLantai10->id,
            'tanggal' => $tanggalBesok,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Kuliah pengganti',
            'keterangan' => 'Membutuhkan projector',
        ]);

        $response->assertRedirect('/history');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservations', [
            'room_id' => $this->roomLantai10->id,
            'user_id' => $this->dosen->id,
            'tanggal' => $tanggalBesok,
            'status' => 'approved',
        ]);
    }

    /**
     * Test reservasi lantai 19 berstatus pending dan harus minimal H+2
     */
    public function test_dosen_can_create_reservation_on_approval_floor_needs_admin_approval_and_must_be_h_plus_2(): void
    {
        $this->actingAs($this->dosen);

        // Coba H+1 (harus gagal karena minimal H+2)
        $tanggalBesok = now()->addDay()->format('Y-m-d');
        $response = $this->post('/reservation/store', [
            'room_id' => $this->roomLantai19->id,
            'tanggal' => $tanggalBesok,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Seminar nasional',
        ]);

        $response->assertSessionHasErrors('tanggal');

        // Coba H+2 (harus berhasil dengan status pending)
        $tanggalHPlus2 = now()->addDays(2)->format('Y-m-d');
        $response2 = $this->post('/reservation/store', [
            'room_id' => $this->roomLantai19->id,
            'tanggal' => $tanggalHPlus2,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'tujuan' => 'Seminar nasional',
        ]);

        $response2->assertRedirect('/history');
        $this->assertDatabaseHas('reservations', [
            'room_id' => $this->roomLantai19->id,
            'tanggal' => $tanggalHPlus2,
            'status' => 'pending',
        ]);
    }

    /**
     * Test reservasi tidak boleh diluar jam operasional (07:00 - 18:30)
     */
    public function test_dosen_cannot_create_reservation_outside_operational_hours(): void
    {
        $this->actingAs($this->dosen);

        $tanggalBesok = now()->addDay()->format('Y-m-d');

        $response = $this->post('/reservation/store', [
            'room_id' => $this->roomLantai10->id,
            'tanggal' => $tanggalBesok,
            'jam_mulai' => '06:00', // Terlalu pagi
            'jam_selesai' => '08:00',
            'tujuan' => 'Rapat pagi',
        ]);

        $response->assertSessionHasErrors('jam_mulai');
    }

    /**
     * Test reservasi tidak boleh bertabrakan waktu
     */
    public function test_dosen_cannot_create_reservation_with_clashing_time(): void
    {
        $tanggalBesok = now()->addDay()->format('Y-m-d');

        // Buat reservasi yang sudah disetujui lebih dulu
        Reservation::create([
            'room_id' => $this->roomLantai10->id,
            'user_id' => $this->dosen->id,
            'tanggal' => $tanggalBesok,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => 'Rapat pertama',
            'status' => 'approved',
        ]);

        // Login dosen kedua
        $dosenKedua = User::create([
            'name' => 'Dr. Siti',
            'email' => 'siti@telkomuniversity.ac.id',
            'role' => 'dosen',
            'nip' => '654321',
        ]);
        $this->actingAs($dosenKedua);

        // Ajukan di jam yang tabrakan (11:00 - 13:00)
        $response = $this->post('/reservation/store', [
            'room_id' => $this->roomLantai10->id,
            'tanggal' => $tanggalBesok,
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
            'tujuan' => 'Rapat kedua',
        ]);

        $response->assertSessionHasErrors('jam_mulai');
    }

    /**
     * Test admin menyetujui reservasi pending
     */
    public function test_admin_can_approve_reservation(): void
    {
        $tanggalHPlus2 = now()->addDays(2)->format('Y-m-d');

        $reservation = Reservation::create([
            'room_id' => $this->roomLantai19->id,
            'user_id' => $this->dosen->id,
            'tanggal' => $tanggalHPlus2,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => 'Seminar',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin);

        $response = $this->from('/admin')->post("/admin/reservations/{$reservation->id}/approve");

        $response->assertRedirect('/admin');
        $this->assertEquals('approved', $reservation->fresh()->status);
        $this->assertEquals($this->admin->id, $reservation->fresh()->approved_by);
    }

    /**
     * Test admin menolak reservasi pending
     */
    public function test_admin_can_reject_reservation(): void
    {
        $tanggalHPlus2 = now()->addDays(2)->format('Y-m-d');

        $reservation = Reservation::create([
            'room_id' => $this->roomLantai19->id,
            'user_id' => $this->dosen->id,
            'tanggal' => $tanggalHPlus2,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'tujuan' => 'Seminar',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin);

        $response = $this->from('/admin')->post("/admin/reservations/{$reservation->id}/reject", [
            'alasan_penolakan' => 'Ruangan dipakai acara Rektorat',
        ]);

        $response->assertRedirect('/admin');
        $this->assertEquals('rejected', $reservation->fresh()->status);
        $this->assertEquals('Ruangan dipakai acara Rektorat', $reservation->fresh()->alasan_penolakan);
    }
}
