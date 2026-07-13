<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin TULT',
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('password'), // ganti sebelum production
            'role' => 'admin',
            'admin_type' => 1,
            'nip' => null,
        ]);

        User::create([
            'name' => 'Admin Lantai 19',
            'email' => 'admin19@telkomuniversity.ac.id',
            'password' => Hash::make('password'), // ganti sebelum production
            'role' => 'admin',
            'admin_type' => 2,
            'nip' => null,
        ]);

        // Dosen tidak pakai password, login cukup dengan NIP + email yang cocok.
        User::create([
            'name' => 'Dr. Ahmad Fauzi',
            'email' => 'ahmad.fauzi@telkomuniversity.ac.id',
            'password' => null,
            'role' => 'dosen',
            'nip' => '123456',
            'phone_number' => '081234567890',
        ]);

        User::create([
            'name' => 'Dr. Siti Nurhaliza',
            'email' => 'siti.nurhaliza@telkomuniversity.ac.id',
            'password' => null,
            'role' => 'dosen',
            'nip' => '654321',
            'phone_number' => '082198765432',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@telkomuniversity.ac.id',
            'password' => null,
            'role' => 'pegawai',
            'nip' => '789012',
            'phone_number' => '081298765432',
        ]);
    }
}
