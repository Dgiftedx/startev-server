<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;

class AddMoreCareerFieldsToIndustries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $industries = [
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'aviation_aeronautics',
                'name' => 'Aviation and Aeronautics'
            ],
            [
                'slug' => uniqid(rand(), true),
                'alias' => 'engineering',
                'name' => 'Engineering'
            ]
        ];


        Model::unguard();

        foreach ($industries as $industry) {
            \App\Models\Industry::create($industry);
        }

        Model::reguard();


    }
}
