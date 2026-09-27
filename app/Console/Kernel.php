<?php

namespace App\Console;

use App\Jobs\Emails\ScheduledEmailsJob;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Log;

class Kernel extends ConsoleKernel {
    protected $commands = [
        Commands\QueueWorkCron::class,
        Commands\QueueRetryCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        if (env('UPGRADING') !== 'YES') {
            $schedule->command('campaign:report')
                ->monthly();
            $schedule->command('sms:send')
                ->everyMinute();
            $schedule->command('email:send')
                ->everyMinute();
            $seconds = 5;

            $schedule->call(function () use ($seconds) {
                $dt = Carbon::now();

                $x = 60 / $seconds;

                do {
                    Log::info('Cron job started at '.$dt->toDateTimeString());

                    time_sleep_until($dt->addSeconds($seconds)->timestamp);
                } while ($x-- > 0);
            })
                ->everyMinute($seconds);
            // $schedule->job(new ScheduledEmailsJob)->everyMinute();

            //Put rest of the schedules here

        }

        // This will only run if UPGRADING variable is set to yes
        $schedule->exec('unzip -o '.storage_path('updates/update.zip'))
            ->when(env('UPGRADING', 'NO') === 'YES')
            ->onSuccessWithOutput(function () {
                overWriteEnvFile('UPGRADING', 'NO');
                $package = file_get_contents(base_path('package.json'));
                $packageJson = json_decode($package);
                overWriteEnvFile('VERSION', $packageJson->version);
                Artisan::call('migrate');
                Artisan::call('storage:link');
                file_put_contents(storage_path('logs/update.log'), 'App updated to '.env('VERSION'));
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
