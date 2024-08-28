<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleadmin = Role::updateorcreate(
            [
                'name'=>'Admin',
            ],
            ['name'=>'Admin']
        );

       $rolebendahara = Role::updateorcreate(
            [
                'name'=>'bendahara',
            ],
            ['name'=>'bendahara']
        );

        $permission = Permission::updateorcreate(
            [
            'name'=> 'view-home',
            ],
            ['name'=>'view-home']
        );
         $permission2 = Permission::updateorcreate(
            [
            'name'=> 'view-admin',
            ],
            ['name'=>'view-admin']
        );
        $roleadmin->givePermissionTo($permission);
        $roleadmin->givePermissionTo($permission2);
        $rolebendahara->givePermissionTo($permission);

        $user = User::find(1);
        $user2 = User::find(4);

        $user->assignRole(['admin']);
        $user2->assignRole(['bendahara']);



    }
}
