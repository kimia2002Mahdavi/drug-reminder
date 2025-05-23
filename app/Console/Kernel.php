<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, string>
     */
    protected $commands = [
        // Commands\ExampleCommand::class,
    ];

    /**
     * Define the application's command scheduling.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new \App\Jobs\ProcessReminders)->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }

}