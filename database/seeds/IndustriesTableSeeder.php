<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'accountants',
                'name' => 'Accountants'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'agricultural_services',
                'name' => 'Agricultural Services & Products'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'transport',
                'name' => 'Transportation'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'architectural',
                'name' => 'Architectural Services'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'automotive',
                'name' => 'Automotive'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'banking_finance',
                'name' => 'Banking & Finance'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'civil_servants',
                'name' => 'Civil Servants/Public Officials'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'computer_software',
                'name' => 'Computer Software'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'education',
                'name' => 'Education'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'medical',
                'name' => 'Medical'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'entertainment',
                'name' => 'Entertainment'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'gas_oil',
                'name' => 'Gas & Oil'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'insurance',
                'name' => 'Insurance'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'economics',
                'name' => 'Economics'
            ],
        ];

        Model::unguard();

        foreach ($industries as $industry) {
            \App\Models\Industry::create($industry);
        }

        Model::reguard();
    }
}
