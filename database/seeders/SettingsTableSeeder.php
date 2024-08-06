<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_waktu')->insert([
            [
                'id_setting' => 1,
                'waktu' => now(),
            ],
            
        ]);
    }
}