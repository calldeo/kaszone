<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Role::updateorcreate(
            [
                'name'=>'admin',
            ],
            ['name'=>'admin']
        );

        Role::updateorcreate(
            [
                'name'=>'bendahara',
            ],
            ['name'=>'bendahara']
        );
     

    }
}
