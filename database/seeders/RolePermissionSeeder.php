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
        Permission::UpdateOrCreate(['name'=>'Home']);
        Permission::UpdateOrCreate(['name'=>'Admin']);
        Permission::UpdateOrCreate(['name'=>'Bendahara']);
        Permission::UpdateOrCreate(['name'=> 'Kategori']);
        Permission::UpdateOrCreate(['name'=> 'Setting']);
        Permission::UpdateOrCreate(['name'=>'Data Pemasukan']);
        Permission::UpdateOrCreate(['name'=>'Data Pengeluaran']);
        Permission::UpdateOrCreate(['name'=>'Laporan']);



        Role::UpdateOrCreate(['name'=>'Admin']);
        Role::UpdateOrCreate(['name'=>'Bendahara']);
        Role::UpdateOrCreate(['name'=>'Reader']);


        $roleAdmin = Role::findByName('Admin');
        $roleAdmin->givePermissionTo('Home');
        $roleAdmin->givePermissionTo('Admin');
        $roleAdmin->givePermissionTo('Bendahara');
        $roleAdmin->givePermissionTo('Kategori');
        $roleAdmin->givePermissionTo('Data Pemasukan');
        $roleAdmin->givePermissionTo('Data Pengeluaran');
        $roleAdmin->givePermissionTo('Laporan');
        $roleAdmin->givePermissionTo('Setting');




        $roleBendahara = Role::findByName('Bendahara');
        $roleBendahara->givePermissionTo('Data Pemasukan');
        $roleBendahara->givePermissionTo('Data Pengeluaran');
        $roleBendahara->givePermissionTo('Home');


        $roleReader = Role::findByName('Reader');
        $roleReader->givePermissionTo('Data Pemasukan');
        $roleReader->givePermissionTo('Data Pengeluaran');
        $roleReader->givePermissionTo('Home');

    }
}
