<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHelpTips01ToHelpTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tips = [
            [
                'target' =>'student',
                'title' => 'Do you know?',
                'content' => 'As a student about to venture into a lucrative business, 
                your business store is automatically set up immediately after your first partnership approval.',
                'link_text' => 'Open Venture Hub',
                'link' => '/venture-hub',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'student',
                'title' => 'Do you know?',
                'content' => 'You can run painless online store without stress',
                'link_text' => 'Open venture Dashboard',
                'link' => '/venture-hub',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'student',
                'title' => 'Do you know?',
                'content' => 'You can earn up to 40% commission when a customer purchase product from your store',
                'link_text' => 'Read more',
                'link' => '/help-center',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'student',
                'title' => 'Get Trained',
                'content' => 'You don\'t know how, get connected with leading mentors in the industry of your interest',
                'link_text' => 'View Mentors List',
                'link' => '/industry',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'mentor',
                'title' => 'Do you know?',
                'content' => 'You can host a live training Broadcast session for your trainees',
                'link_text' => 'See How',
                'link' => '/broadcast',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ]
        ];

        foreach ($tips as $tip) {
            \Illuminate\Support\Facades\DB::table('help_tips')->insert($tip);
        }
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
