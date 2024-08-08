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
                'name' => 'Deo Andreas',
                'level' => 'admin',
                'kelamin' => 'laki-laki',
                'kelas' => null,
                'email' => 'deo@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('callmedeo'),  // Menggunakan bcrypt untuk enkripsi password
                'alamat' => 'Bondowoso',
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Restu',
                'level' => 'bendahara',
                'kelamin' => 'laki-laki',
                'kelas' => null,
                'email' => 'restu@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('callmedeo'),  // Menggunakan bcrypt
                'alamat' => 'Bondowoso',
                'remember_token' => Str::random(10),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);
    }
}
