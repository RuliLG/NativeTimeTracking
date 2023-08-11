<?php

namespace App\Console;

use App\Models\Configuration;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Native\Laravel\Facades\MenuBar;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $configuration = Configuration::first();
            logger()->debug($configuration);
            if (is_null($configuration) || is_null($configuration->provider_config['profile'] ?? null)) {
                return;
            }

            logger()->debug('Syncing attendances');
            $provider = Configuration::timeTrackingProvider();
            $provider->syncAttendances();
            $trackedToday = $provider->timeTrackedToday();
            $minutesToHoursAndMinutes = function ($minutes) {
                return sprintf('%01dh %02dm', $minutes / 60, $minutes % 60);
            };
            $trackedToday = $minutesToHoursAndMinutes($trackedToday);
            MenuBar::label(' Tracked ' . $trackedToday);
        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
