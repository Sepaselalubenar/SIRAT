<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin TULT',
                'email' => 'admin@telkomuniversity.ac.id',
                'email_verified_at' => null,
                'password' => '$2y$12$BR253mEPTkOjwx4S.z8MgO3oA22/ssW1Jab1of0hxdwjofeqsAfGq',
                'remember_token' => null,
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-07 02:54:08',
                'role' => 'admin',
                'admin_type' => 1,
                'nip' => null,
            ],
            [
                'id' => 2,
                'name' => 'Dr. Ahmad Fauzi',
                'email' => 'ahmad.fauzi@telkomuniversity.ac.id',
                'email_verified_at' => null,
                'password' => null,
                'remember_token' => null,
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-07 02:54:08',
                'role' => 'dosen',
                'admin_type' => null,
                'nip' => '123456',
            ],
            [
                'id' => 3,
                'name' => 'Dr. Siti Nurhaliza',
                'email' => 'siti.nurhaliza@telkomuniversity.ac.id',
                'email_verified_at' => null,
                'password' => null,
                'remember_token' => null,
                'created_at' => '2026-07-07 02:54:08',
                'updated_at' => '2026-07-07 02:54:08',
                'role' => 'dosen',
                'admin_type' => null,
                'nip' => '654321',
            ],
            [
                'id' => 4,
                'name' => 'Zidane Dinar',
                'email' => 'zidanedinar11@gmail.com',
                'email_verified_at' => null,
                'password' => null,
                'remember_token' => null,
                'created_at' => '2026-07-08 03:32:41',
                'updated_at' => '2026-07-08 03:32:41',
                'role' => 'dosen',
                'admin_type' => null,
                'nip' => '111204',
            ],
            [
                'id' => 5,
                'name' => 'Zidane Dinar',
                'email' => 'admin2@google.com',
                'email_verified_at' => null,
                'password' => '$2y$12$uXu0NdSka1BxFZBoJ0oiz.uMaSYuELplU4XAhJmhgSZMeoPKjWNy2',
                'remember_token' => null,
                'created_at' => '2026-07-09 14:59:23',
                'updated_at' => '2026-07-09 14:59:23',
                'role' => 'admin',
                'admin_type' => 1,
                'nip' => null,
            ],
            [
                'id' => 6,
                'name' => 'Admin Lantai 19',
                'email' => 'admin19@telkomuniversity.ac.id',
                'email_verified_at' => null,
                'password' => '$2y$12$BR253mEPTkOjwx4S.z8MgO3oA22/ssW1Jab1of0hxdwjofeqsAfGq', // 'password'
                'remember_token' => null,
                'created_at' => '2026-07-13 18:00:00',
                'updated_at' => '2026-07-13 18:00:00',
                'role' => 'admin',
                'admin_type' => 2,
                'nip' => null,
            ]
        ]);

        if (config('database.default') === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE(MAX(id), 1)) FROM users");
        }
    }
}
