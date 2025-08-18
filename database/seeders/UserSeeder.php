<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \Backpack\PermissionManager\app\Models\Role::create([
            'name' => 'admin',
        ]);



        \Backpack\PermissionManager\app\Models\Role::create([
            'name' => 'customer',
        ]);

        $admin = User::updateOrCreate([
            'name'       => 'mfonseca',
            'first_name' => 'Luisa Mayela',
            'last_name'  => 'Fonseca Picado',
            'email'      => 'mfonseca2106@gmail.com'
        ], [
            'name'       => 'mfonseca',
            'first_name' => 'Luisa Mayela',
            'last_name'  => 'Fonseca',
            'email'      => 'mfonseca2106@gmail.com',
            'password'   => bcrypt('Rmfonseca2025')
        ]);

        $admin->assignRole('customer');




        $admin = User::updateOrCreate([
            'name'       => 'Admin',
            'first_name' => 'Admin',
            'last_name'  => 'admin',

        ], [
            'name'       => 'Admin',
            'first_name' => 'Admin',
            'last_name'  => 'admin',
            'email'      => 'admin@superlocales.com',
            'password'   => bcrypt('R0d0lfit0!')
        ]);


        $admin->assignRole('admin');
    }
}
