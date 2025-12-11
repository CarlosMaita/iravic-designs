<?php

namespace App\Console;

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
        \App\Console\Commands\PopulateProductSlugs::class,
        \App\Console\Commands\UpdateExchangeRate::class,
        \App\Console\Commands\MakeWebp::class,
        \App\Console\Commands\SendReviewRequestEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Update exchange rate from BCV every hour
        $schedule->command('exchange-rate:update')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Send review request emails daily
        $schedule->command('notifications:send-review-requests')
                 ->daily()
                 ->at('10:00');
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
