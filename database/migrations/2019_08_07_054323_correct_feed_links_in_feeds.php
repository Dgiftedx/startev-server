<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectFeedLinksInFeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $feeds = \App\Models\Feed::orderBy('id','desc')->get(['id','body']);
        $convertToLink = new \Nahid\Linkify\Linkify(array('attr' => array('class' => 'feed-link', 'target' => '_blank')));

        foreach ($feeds as $feed) {
            $converted = $convertToLink->process($feed->body);
            \App\Models\Feed::find($feed->id)->update(['body' => $converted]);
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
