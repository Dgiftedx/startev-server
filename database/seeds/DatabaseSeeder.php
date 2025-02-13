<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(IndustriesTableSeeder::class);
         $this->call(CareerPathTableSeeder::class);
           $this->call(RolesTableSeeder::class);
    }
}
