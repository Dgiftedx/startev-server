<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyHelpTips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \App\Models\HelpTip::query()->truncate();

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
                'target' =>'graduate',
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
                'content' => 'You can run painless online store without stress. Every single order is handled by the business to let you focus more on your customer satisfaction.',
                'link_text' => 'Open venture Dashboard',
                'link' => '/venture-dashboard',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'graduate',
                'title' => 'Do you know?',
                'content' => 'You can run painless online store without stress. Every single order is handled by the business to let you focus more on your customer satisfaction.',
                'link_text' => 'Open venture Dashboard',
                'link' => '/venture-dashboard',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],

            [
                'target' =>'student',
                'title' => 'Do you know?',
                'content' => 'You can earn up to 40% commission when a customer purchase product from your store or use your referral code.',
                'link_text' => 'Read more',
                'link' => '',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'student',
                'title' => 'Get Trained',
                'content' => 'You don\'t know how to go about the business idea? Get connected with leading mentors in the industry of your interest',
                'link_text' => 'View Mentors List',
                'link' => '/industry',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'graduate',
                'title' => 'Get Trained',
                'content' => 'You don\'t know how to go about the business idea? Get connected with leading mentors in the industry of your interest',
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
                'link' => '/messages',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'mentor',
                'title' => 'Do you know?',
                'content' => 'You can publish great materials in our knowledge hub to help students and graduates grow.',
                'link_text' => 'See How',
                'link' => '/my-publications',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'target' =>'mentor',
                'title' => 'Have you verified your account?',
                'content' => 'Account verification strengthens your profile and also earns you peoples trust.',
                'link_text' => 'See How',
                'link' => '/edit-profile',
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
