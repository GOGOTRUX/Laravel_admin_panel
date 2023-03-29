<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\put_op_uid;
use App\Console\Commands\DatabaseBackup;
use App\Console\Commands\activateSubplans;
use App\Console\Commands\expireSubscription;
use App\Console\Commands\CreateOperatorAccount;
use App\Console\Commands\updateUIDInOperatorAccountTable;
use App\Console\Commands\replaceDuplicatedUIDs;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\put_op_uid',
        'App\Console\Commands\DatabaseBackup',
        'App\Console\Commands\activateSubplans',
        'App\Console\Commands\expireSubscription',
        'App\Console\Commands\CreateOperatorAccount',
        'App\Console\Commands\updateUIDInOperatorAccountTable',
        'App\Console\Commands\replaceDuplicatedUIDs',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:DatabaseBackup')->dailyAt('00:00');
        $schedule->command('command:activateSubplans')->dailyAt('00:00');
	$schedule->command('command:expireSubscription')->dailyAt('00:00');
	$schedule->command('command:getMapAccessToken')->dailyAt('00:00');
        // $schedule->command('command:CreateOperatorAccount')->dailyAt('00:00');
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
