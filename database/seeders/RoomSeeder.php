<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rooms')->delete();

        DB::table('rooms')->insert([
            [
                'id' => 11,
                'nama' => 'Ruang Rapat FTE 19.07',
                'jenis' => 'Ruang Rapat FTE',
                'lantai' => '19',
                'kapasitas' => 30,
                'fasilitas' => '["1 Meja U","2 Meja Panjang","10 Meja Kecil","1 Meja Operator","1 Dispenser","1 Alat Kopi","1 Set PC Operator","1 Smart TV","1 Webcam","1 Speaker","4 Microphone","30 Kursi"]',
                'deskripsi' => 'Ruang meeting lantai 19, memerlukan persetujuan admin.',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:22:14',
            ],
            [
                'id' => 10,
                'nama' => 'Ruang Rapat 14.11',
                'jenis' => 'Ruang Rapat',
                'lantai' => '14',
                'kapasitas' => 30,
                'fasilitas' => '["1 Meja U","2 Meja Panjang","1 Kabinet","1 Smart TV","1 Dispenser","30 Kursi"]',
                'deskripsi' => 'Ruang meeting lantai 14.',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:22:42',
            ],
            [
                'id' => 9,
                'nama' => 'Ruang Rapat Lantai 3',
                'jenis' => 'Ruang Rapat',
                'lantai' => '3',
                'kapasitas' => 20,
                'fasilitas' => '["1 Meja Besar","10 Meja Kecil","1 Kabinet","1 Smart TV","20 Kursi"]',
                'deskripsi' => 'Ruang rapat yang berada di lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:25:49',
            ],
            [
                'id' => 1,
                'nama' => 'Ruangan 03.01',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Glassboard","1 Proyektor","1 Smart TV"]',
                'deskripsi' => 'Ruangan di lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:31:09',
            ],
            [
                'id' => 2,
                'nama' => 'Ruangan 03.02',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Glassboard","1 Proyektor"]',
                'deskripsi' => 'Ruangan lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:32:48',
            ],
            [
                'id' => 3,
                'nama' => 'Ruangan 03.03',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Proyektor","1 Glassboard"]',
                'deskripsi' => 'Ruangan pada lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:34:24',
            ],
            [
                'id' => 4,
                'nama' => 'Ruangan 03.04',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Proyektor","1 Glassboard"]',
                'deskripsi' => 'Ruangan yang berada di lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:36:06',
            ],
            [
                'id' => 5,
                'nama' => 'Ruangan 03.05',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Proyektor","1 Glassboard","1 Kabinet"]',
                'deskripsi' => 'Ruangan yang ada di lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:38:28',
            ],
            [
                'id' => 6,
                'nama' => 'Ruangan 03.06',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Proyektor","1 Glassboard"]',
                'deskripsi' => 'Ruangan di lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:40:14',
            ],
            [
                'id' => 7,
                'nama' => 'Ruangan 03.07',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Proyektor","1 Glassboard"]',
                'deskripsi' => 'Ruangan pada lantai 3',
                'status' => 'tersedia',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-09 14:41:41',
            ],
            [
                'id' => 8,
                'nama' => 'Ruangan 03.08',
                'jenis' => 'Ruangan',
                'lantai' => '3',
                'kapasitas' => 6,
                'fasilitas' => '["1 Meja","6 Kursi","1 Proyektor","1 Glassboard"]',
                'deskripsi' => 'Ruangan berada di lantai 3',
                'status' => 'dipakai',
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-10 10:45:21',
            ]
        ]);

        if (config('database.default') === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('rooms', 'id'), COALESCE(MAX(id), 1)) FROM rooms");
        }
    }
}
