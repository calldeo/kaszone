<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['name'=>'home']);
        Permission::create(['name'=>'admin']);
        Permission::create(['name'=>'bendahara']);
        Permission::create(['name'=> 'kategori']);


        Permission::create(['name'=>'datapemasukan']);
        Permission::create(['name'=>'datapengeluaran']);

        Role::create(['name'=>'admin']);
        Role::create(['name'=>'bendahara']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('home');
        $roleAdmin->givePermissionTo('admin');
        $roleAdmin->givePermissionTo('bendahara');
        $roleAdmin->givePermissionTo('kategori');
        $roleAdmin->givePermissionTo('datapemasukan');
        $roleAdmin->givePermissionTo('datapengeluaran');



        $roleBendahara = Role::findByName('bendahara');
         $roleBendahara->givePermissionTo('datapemasukan');
        $roleBendahara->givePermissionTo('datapengeluaran');
        $roleBendahara->givePermissionTo('home');

    }
}
