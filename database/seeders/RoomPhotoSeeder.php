<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomPhotoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('room_photos')->delete();

        DB::table('room_photos')->insert([
            [
                'id' => 1,
                'room_id' => 11,
                'path' => 'rooms/wdsZH3HvR6LXmzuSfxDeqhp99moG1OMEDzi3wqWb.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 13:53:23',
                'updated_at' => '2026-07-09 13:53:23',
            ],
            [
                'id' => 2,
                'room_id' => 11,
                'path' => 'rooms/PQ2FvLRhN7RIHUzV3KHsTW0KM3j9ewOdxjLqcZKM.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 13:53:35',
                'updated_at' => '2026-07-09 13:53:35',
            ],
            [
                'id' => 3,
                'room_id' => 11,
                'path' => 'rooms/rZAJROdTCKmZ2EMjlK5THnRI2H8wa2KzsmyJVwSN.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 13:56:22',
                'updated_at' => '2026-07-09 13:56:22',
            ],
            [
                'id' => 5,
                'room_id' => 11,
                'path' => 'rooms/QyO65Vy5Rj6zTW6PpR8Us38J4fNfR7xLewJDelfC.jpg',
                'urutan' => 4,
                'created_at' => '2026-07-09 13:59:10',
                'updated_at' => '2026-07-09 13:59:10',
            ],
            [
                'id' => 6,
                'room_id' => 10,
                'path' => 'rooms/WzV1plWcF9t11EEXqupzREMjOhIL23gF8YbxPd2G.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:18:50',
                'updated_at' => '2026-07-09 14:18:50',
            ],
            [
                'id' => 7,
                'room_id' => 10,
                'path' => 'rooms/D3a8Voc7RK4Op7sRsoi841e6VsRHBKLm9BZv0BZQ.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:18:55',
                'updated_at' => '2026-07-09 14:18:55',
            ],
            [
                'id' => 8,
                'room_id' => 10,
                'path' => 'rooms/PeGatpAZOEaDpWHtcRCDY0ogPKhgXk5co0hPZP8s.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 14:18:59',
                'updated_at' => '2026-07-09 14:18:59',
            ],
            [
                'id' => 9,
                'room_id' => 10,
                'path' => 'rooms/DDQBEjRQFs4mnmPYc9rxap5l4xZHeiMn8gaf0G6O.jpg',
                'urutan' => 4,
                'created_at' => '2026-07-09 14:19:04',
                'updated_at' => '2026-07-09 14:19:04',
            ],
            [
                'id' => 10,
                'room_id' => 9,
                'path' => 'rooms/HseEZT8NtlGyr3Cndlmy1yZnTksomQ8ywbvAocht.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:24:38',
                'updated_at' => '2026-07-09 14:24:38',
            ],
            [
                'id' => 11,
                'room_id' => 9,
                'path' => 'rooms/Y0pFPVkrlvIsXXUOW5FKEMPh65LlbD90Rtt72kvg.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:24:47',
                'updated_at' => '2026-07-09 14:24:47',
            ],
            [
                'id' => 13,
                'room_id' => 9,
                'path' => 'rooms/GFagqAIiG6D580ilKVII9YYih438zxcPySaITbxM.jpg',
                'urutan' => 4,
                'created_at' => '2026-07-09 14:25:14',
                'updated_at' => '2026-07-09 14:25:14',
            ],
            [
                'id' => 14,
                'room_id' => 9,
                'path' => 'rooms/dkO09IMKLGpUYAYRHNSdzJkFivN67AlKEJNOzRb2.jpg',
                'urutan' => 5,
                'created_at' => '2026-07-09 14:25:32',
                'updated_at' => '2026-07-09 14:25:32',
            ],
            [
                'id' => 15,
                'room_id' => 1,
                'path' => 'rooms/pfraABVLoGBgoIEjFgezei7ptkRVNs66Ha9vfzx0.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:30:17',
                'updated_at' => '2026-07-09 14:30:17',
            ],
            [
                'id' => 16,
                'room_id' => 1,
                'path' => 'rooms/Nn35gZ392MvYcRoJi7CkNG5jX5kvfmcyLJmc96kS.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:30:33',
                'updated_at' => '2026-07-09 14:30:33',
            ],
            [
                'id' => 17,
                'room_id' => 1,
                'path' => 'rooms/iMUEZASSa22swyfQ7YoyfnUGcoN1mRjZAFPUrFT5.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 14:30:52',
                'updated_at' => '2026-07-09 14:30:52',
            ],
            [
                'id' => 18,
                'room_id' => 1,
                'path' => 'rooms/0mRGYkepglLW4aZe4ISbOrKJZZOglv1cstoqUTlo.jpg',
                'urutan' => 4,
                'created_at' => '2026-07-09 14:30:56',
                'updated_at' => '2026-07-09 14:30:56',
            ],
            [
                'id' => 19,
                'room_id' => 3,
                'path' => 'rooms/7MqKu8wjNdKFNiJx0QKQXWg1C5I5GSNutTe7Y8v7.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:34:12',
                'updated_at' => '2026-07-09 14:34:12',
            ],
            [
                'id' => 20,
                'room_id' => 3,
                'path' => 'rooms/p5K8CmSTTm6PbQ0ZZKENvaWDNVM6HIDXolKZiB0i.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:34:17',
                'updated_at' => '2026-07-09 14:34:17',
            ],
            [
                'id' => 21,
                'room_id' => 3,
                'path' => 'rooms/gO79gLcT5IoYPq95cVfog8BIMuN0w7gzaXm4c425.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 14:34:21',
                'updated_at' => '2026-07-09 14:34:21',
            ],
            [
                'id' => 22,
                'room_id' => 4,
                'path' => 'rooms/sOKLDF5zUiJDMQLulYS1nM2sQ7EJATA5Xwmn1zBq.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:35:31',
                'updated_at' => '2026-07-09 14:35:31',
            ],
            [
                'id' => 23,
                'room_id' => 4,
                'path' => 'rooms/PDpD94bQxWRqY9zasz7DbJ42pfag8TJaf1RyPTKr.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:35:36',
                'updated_at' => '2026-07-09 14:35:36',
            ],
            [
                'id' => 25,
                'room_id' => 4,
                'path' => 'rooms/juxNXv4stx9zrQzVRGyXMMgF8G9hlgP1FLpEVEjM.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 14:36:03',
                'updated_at' => '2026-07-09 14:36:03',
            ],
            [
                'id' => 26,
                'room_id' => 5,
                'path' => 'rooms/5GY29QfCzBWsu5DleU1fGbYJWzsFi6uywMKSfc5T.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:38:16',
                'updated_at' => '2026-07-09 14:38:16',
            ],
            [
                'id' => 27,
                'room_id' => 5,
                'path' => 'rooms/iJjZQ6JMNaZs792J3llMujylT4bbbKn8PA1OMIQX.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:38:20',
                'updated_at' => '2026-07-09 14:38:20',
            ],
            [
                'id' => 28,
                'room_id' => 6,
                'path' => 'rooms/rAXDT4mjmEzUANEynLjPDmLQKR7JRHSw3s8Lk9TG.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:40:02',
                'updated_at' => '2026-07-09 14:40:02',
            ],
            [
                'id' => 29,
                'room_id' => 6,
                'path' => 'rooms/U2y49IbrOTCUhipTsw4qmzAII2HMOoR99cEBEqeN.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:40:06',
                'updated_at' => '2026-07-09 14:40:06',
            ],
            [
                'id' => 30,
                'room_id' => 6,
                'path' => 'rooms/bi9rCjFjufEWZZnJaNkl7jLBwMrFsqADKK5PVW8M.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 14:40:10',
                'updated_at' => '2026-07-09 14:40:10',
            ],
            [
                'id' => 31,
                'room_id' => 7,
                'path' => 'rooms/lOq7U0wZUWv3UqkZBQw0yQs3VjC5G4ftyw8zA0ED.jpg',
                'urutan' => 1,
                'created_at' => '2026-07-09 14:41:26',
                'updated_at' => '2026-07-09 14:41:26',
            ],
            [
                'id' => 32,
                'room_id' => 7,
                'path' => 'rooms/WZLy4b8g9Z5fuBvPV0Fvup622eyZggDLQoyKhQ2d.jpg',
                'urutan' => 2,
                'created_at' => '2026-07-09 14:41:31',
                'updated_at' => '2026-07-09 14:41:31',
            ],
            [
                'id' => 33,
                'room_id' => 7,
                'path' => 'rooms/OAK1iUgmDBKByAmqa9RbNQ74tUifb12Twwp8MYoL.jpg',
                'urutan' => 3,
                'created_at' => '2026-07-09 14:41:36',
                'updated_at' => '2026-07-09 14:41:36',
            ]
        ]);

        if (config('database.default') === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('room_photos', 'id'), COALESCE(MAX(id), 1)) FROM room_photos");
        }
    }
}
