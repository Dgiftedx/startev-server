<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;

class UpdateCareerFieldsValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Industry::query()->truncate();
        \Illuminate\Support\Facades\DB::table('user_industry')->truncate();


        $industries = [
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'fashion',
                'name' => 'Fashion'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'automobile_logistics',
                'name' => 'Automobile and Logistics'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'real_estate_architectural_science',
                'name' => 'Real Estate and Architectural science'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'manufacturing',
                'name' => 'Manufacturing'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'consulting',
                'name' => 'Consulting'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'agriculture',
                'name' => 'Agriculture'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'hospitality_hotels',
                'name' => 'Hospitality (hotels)'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'tourism',
                'name' => 'Tourism'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'entertainment_creative_art',
                'name' => 'Entertainment and Creative Art'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'environmental_science',
                'name' => 'Environmental science'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'education',
                'name' => 'Education'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'ict',
                'name' => 'ICT'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'banking_finance',
                'name' => 'Banking and Finance'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'insurance',
                'name' => 'Insurance'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'accounting_economics',
                'name' => 'Accounting & Economics'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'civil_service',
                'name' => 'Civil service'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'oil_gas',
                'name' => 'Oil & Gas'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'medicine_pharmacy',
                'name' => 'Medicine & Pharmacy'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'law',
                'name' => 'Law'
            ],
        ];

        Model::unguard();

        foreach ($industries as $industry) {
            \App\Models\Industry::create($industry);
        }

        Model::reguard();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
