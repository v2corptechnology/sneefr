<?php

namespace Sneefr\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Sneefr\Models\Evaluation;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Sneefr\Console\Commands\ImportYelpShop::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Very frequent calls

        if (config('sneefr.RUN_YELP_IMPORT', false)) {
            $schedule->command('yelp:import')->everyMinute();
            $schedule->command('scout:import',  ['\\Sneefr\\Models\\Shop'])->weekly();
        }

        // Calls made every hour

        $schedule->call(function () {
            app('Illuminate\Contracts\Bus\Dispatcher')
                ->dispatch(app('Sneefr\Jobs\BackupDatabase'));
        })->hourly();

        $schedule->call(function () {
            app('Illuminate\Contracts\Bus\Dispatcher')
                ->dispatch(app('Sneefr\Jobs\DeleteOutdatedTemporaryImages'));
        })->hourly();

        $schedule->call(function () {
            // Force outdated evaluations
            Evaluation::pending()->daysOld(10)->update(['status' => Evaluation::STATUS_FORCED]);
        })->daily();

        // Daily calls

        $schedule->call(function () {
            app('Illuminate\Contracts\Bus\Dispatcher')
                ->dispatch(app('Sneefr\Jobs\RemoveOutdatedDatabaseDumps'));
        })->dailyAt('04:00');

        // Sporadic calls

        $schedule->exec('(composer outdated --outdated && npm outdated)')
            ->sendOutputTo(storage_path('app/outDatedDependencies.log'))
            ->emailOutputTo('romain.sauvaire@gmail.com')
            ->weekly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
