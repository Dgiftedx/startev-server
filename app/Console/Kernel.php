<?php

namespace App\Console;

use App\Http\Controllers\CronController;
use App\Repositories\RequestBusinessPayout;
use function Clue\StreamFilter\fun;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
          $schedule->call(function () {
              (new CronController)->payBusiness();
              (new CronController)->payStores();
          })->dailyAt('07:00')->name('payout_job')->withoutOverlapping(10);;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
