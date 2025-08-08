<?php

namespace App\Console;

use App\Console\Commands\ccStatus;
use Illuminate\Console\Scheduling\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ccStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('command.cc_status')->everyMinute();
        $schedule->command('command.cc_status')
            ->weeklyOn(3, '8:00') // 3 = Wednesday
            ->when(function () {
                $today = Carbon::today();
                $lastWednesday = Carbon::now()
                    ->endOfMonth()
                    ->previous(Carbon::WEDNESDAY);

                return $today->isSameDay($lastWednesday);
            });
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
