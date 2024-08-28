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
        Permission::create(['name'=>'Home']);
        Permission::create(['name'=>'Admin']);
        Permission::create(['name'=>'Bendahara']);
        Permission::create(['name'=> 'Kategori']);
        Permission::create(['name'=> 'Role']);


        Permission::create(['name'=>'Data Pemasukan']);
        Permission::create(['name'=>'Data Pengeluaran']);

        Role::create(['name'=>'Admin']);
        Role::create(['name'=>'Bendahara']);

        $roleAdmin = Role::findByName('Admin');
        $roleAdmin->givePermissionTo('Home');
        $roleAdmin->givePermissionTo('Admin');
        $roleAdmin->givePermissionTo('Bendahara');
        $roleAdmin->givePermissionTo('Kategori');
        $roleAdmin->givePermissionTo('Data Pemasukan');
        $roleAdmin->givePermissionTo('Data Pengeluaran');
        $roleAdmin->givePermissionTo('Role');




        $roleBendahara = Role::findByName('Bendahara');
         $roleBendahara->givePermissionTo('Data Pemasukan');
        $roleBendahara->givePermissionTo('Data Pengeluaran');
        $roleBendahara->givePermissionTo('Home');

    }
}
