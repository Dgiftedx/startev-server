<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publication_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name');
            $table->string('category_slug');
            $table->timestamps();
        });


        $categories = [
            [
                'category_name' => 'All Career Fields',
                'category_slug' => 'all_career_fields',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'category_name' => 'Career Development',
                'category_slug' => 'career_development',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'category_name' => 'General Knowledge Areas',
                'category_slug' => 'general_knowledge_areas',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'category_name' => 'Entrepreneurship and Business Management',
                'category_slug' => 'entrepreneurship_business_management',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
        ];

        foreach ($categories as $category) {
            \Illuminate\Support\Facades\DB::table('publication_categories')->insert($category);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publication_categories');
    }
}
