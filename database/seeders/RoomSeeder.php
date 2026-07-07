<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $fasilitasSidang = ['TV 55 inch', 'Proyektor', 'Whiteboard', 'AC', 'WiFi', 'Sound System', 'Meja Sidang'];
        $fasilitasMeeting = ['TV 55 inch', 'Proyektor', 'Whiteboard', 'AC', 'WiFi'];

        $rooms = [];

        // Lantai 3: 8 ruang sidang + 1 ruang meeting = 9 ruangan (langsung approved kalau kosong)
        for ($i = 1; $i <= 8; $i++) {
            $rooms[] = [
                'nama' => 'Ruang Sidang ' . (300 + $i),
                'jenis' => 'Ruang Sidang',
                'lantai' => '3',
                'kapasitas' => 20,
                'fasilitas' => json_encode($fasilitasSidang),
                'deskripsi' => 'Ruang sidang berkapasitas 20 orang, dilengkapi dengan fasilitas presentasi dan meeting.',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $rooms[] = [
            'nama' => 'Ruang Meeting Lantai 3',
            'jenis' => 'Ruang Meeting',
            'lantai' => '3',
            'kapasitas' => 12,
            'fasilitas' => json_encode($fasilitasMeeting),
            'deskripsi' => 'Ruang meeting kecil untuk diskusi tim.',
            'status' => 'tersedia',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Lantai 14: langsung approved kalau kosong
        $rooms[] = [
            'nama' => 'Ruang Meeting 14.11',
            'jenis' => 'Ruang Meeting',
            'lantai' => '14',
            'kapasitas' => 15,
            'fasilitas' => json_encode($fasilitasMeeting),
            'deskripsi' => 'Ruang meeting lantai 14.',
            'status' => 'tersedia',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Lantai 19: butuh approval admin + minimal H+2
        $rooms[] = [
            'nama' => 'Ruang Meeting 19.07',
            'jenis' => 'Ruang Meeting',
            'lantai' => '19',
            'kapasitas' => 10,
            'fasilitas' => json_encode($fasilitasMeeting),
            'deskripsi' => 'Ruang meeting lantai 19, memerlukan persetujuan admin.',
            'status' => 'tersedia',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('rooms')->insert($rooms);
    }
}
