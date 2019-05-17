<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CareerPathTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $careers = [
            [
                'slug' => uniqid(rand()),
                'name' => 'Agribusiness'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Construction'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Telecommunications'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Visual Arts'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Business Information Management'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Human Resource Management'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Teaching & Training'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Business finance'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Banking Services'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Insurance'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Revenue & Taxation'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Health Informatics'
            ],
            [
                'slug' => uniqid(rand()),
                'name' => 'Programming & Software Development'
            ]
        ];


        Model::unguard();

        foreach ($careers as $career) {
            \App\Models\CareerPath::create($career);
        }

        Model::reguard();
    }
}
