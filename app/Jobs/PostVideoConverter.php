<?php

namespace App\Jobs;

use App\Http\Controllers\HelperController;
use App\Models\Feed;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use FFMpeg\Format\Video\WebM;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;
use PhpParser\Node\Scalar\MagicConst\File;
use Pusher\Laravel\Facades\Pusher;

class PostVideoConverter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoDetails;

    /**
     * PostVideoConverter constructor.
     * @param $details
     */
    public function __construct( $details )
    {
        $this->videoDetails = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = $this->videoDetails['file'];
        $fileName = $this->videoDetails['file'];

        $extension = explode(".", $filePath)[1];

        if($extension != "webm"){
            $lowBitrateFormat = (new WebM)->setKiloBitrate(500);
            FFMpeg::fromDisk('video')
                ->open($filePath)
                ->export()
                ->toDisk('video')
                ->inFormat($lowBitrateFormat)
                ->save($fileName . ".webm");

        }

        $video = FFMpeg::fromDisk('video')->open($filePath);

        Log::info(json_encode($this->videoDetails));
        $video->getFrameFromSeconds(10)
            ->export()
            ->toDisk('video')
            ->save($fileName.'.png');

        $feedData = [
            'postType' => $this->videoDetails['post_type'],
            'roleData' => HelperController::fetchRoleData($this->videoDetails['user_id']),
            'user' => User::where('id','=',$this->videoDetails['user_id'])->first(['id','slug','name','avatar']),
            'title' => $this->videoDetails['title'],
            'body' => 'N/A',
            'image' => $fileName . '.png',
            'video' => "video/". $fileName . ".webm",
            'hasLiked' => 0,
            'time' => Carbon::now()
        ];

        $databaseUpdate = [
            'user_id' => $this->videoDetails['user_id'],
            'title' => $this->videoDetails['title'],
            'body' => 'N/A',
            'post_type' => $this->videoDetails['post_type'],
            'image' => $fileName . '.png',
            'video' => "video/". $fileName . ".webm",
            'hasLiked' => 0,
            'time' => Carbon::now()
        ];

        $update = Feed::create($databaseUpdate);

        $feedData['id'] = $update->id;

        Pusher::trigger('my-channel', 'my-event', $feedData);

        $user = User::find($this->videoDetails['user_id']);

        UserNotification::create(['user_id' => $this->videoDetails['user_id'], 'target_id' => 0, 'title' => "New feed has been published", 'content' => "{$user->name} published  {$this->videoDetails['title']} to news feed."]);
    }
}
