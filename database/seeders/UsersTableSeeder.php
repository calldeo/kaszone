<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = User::firstOrCreate(
    ['email' => 'deo@gmail.com'],
    [
        'name' => 'Deo Andreas',
        'kelamin' => 'laki-laki',
        'kelas' => null,
        'password' => bcrypt('callmedeo'),
        'alamat' => 'Bondowoso',
        'remember_token' => Str::random(10),
        'deleted_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]
);
$admin->assignRole('admin', 'bendahara');

$bendahara = User::firstOrCreate(
    ['email' => 'deo1@gmail.com'],  
    [
        'name' => 'Deo Andreas',
        'kelamin' => 'laki-laki',
        'kelas' => null,
        'password' => bcrypt('callmedeo'),
        'alamat' => 'Bondowoso',
        'remember_token' => Str::random(10),
        'deleted_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]
);
$bendahara->assignRole('bendahara');

$reader = User::firstOrCreate(
    ['email' => 'deo2@gmail.com'],  
    [
        'name' => 'Deo Andreas',
        'kelamin' => 'laki-laki',
        'kelas' => null,
        'password' => bcrypt('callmedeo'),
        'alamat' => 'Bondowoso',
        'remember_token' => Str::random(10),
        'deleted_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]
);
$reader->assignRole('reader');

    }
}