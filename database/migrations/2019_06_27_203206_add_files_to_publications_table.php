<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilesToPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('videoSource');
            $table->dropColumn('videoLink');
            $table->integer('industry_id')->nullable()->after('content');
            $table->integer('category_id')->default(3)->after('id');
            $table->json('images')->nullable()->after('content');
            $table->json('files')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publications', function (Blueprint $table) {
            //
        });
    }
}
