<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Gilang Nugraha',
                'level' => 'admin',
                'kelas' => 'XII RPL',
                'email' => 'gilang32nugraha@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('123123123'),  // Menggunakan bcrypt untuk enkripsi password
                'status_pemilihan' => 'Belum Memilih',
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vishal',
                'level' => 'guru',
                'kelas' => null,
                'email' => 'vishal@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('123123123'),  // Menggunakan bcrypt
                'status_pemilihan' => 'Belum Memilih',
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Restu Aji',
                'level' => 'siswa',
                'kelas' => 'XII RPL 1',
                'email' => 'adi.putra@example.com',
                'email_verified_at' => null,
                'password' => bcrypt('123123123'),  // Menggunakan bcrypt
                'status_pemilihan' => 'Belum Memilih',
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
