<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'super', 'guard_name' => 'admin'],
            ['name' => 'moderator', 'guard_name' => 'admin'],
            ['name' => 'customer_care', 'guard_name' => 'admin'],
        ];


        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create($role);
        }


        $admin = [
            'slug' => uniqid(rand()),
            'name' => 'Core Administrator',
            'email' => 'admin@startev.africa',
            'username' => 'administrator',
            'official_phone' => '09074673652',
            'password' => bcrypt('startevAdmin')
        ];

        $newUser = \App\Models\Admin\Admin::create($admin);

        $newUser->assignRole(['super','moderator','customer_care']);
    }
}
