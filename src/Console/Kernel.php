<?php

namespace Larafast\Fastapi\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Larafast\Fastapi\Console\Commands\ControllerMakeCommand;
use Larafast\Fastapi\Console\Commands\FactoryMakeCommand;
use Larafast\Fastapi\Console\Commands\Fastapi;
use Larafast\Fastapi\Console\Commands\MigrateMakeCommand;
use Larafast\Fastapi\Console\Commands\ModelMakeCommand;
use Larafast\Fastapi\Console\Commands\RequestMakeCommand;
use Larafast\Fastapi\Console\Commands\ResourceMakeCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ControllerMakeCommand::class,
        FactoryMakeCommand::class,
        Fastapi::class,
        MigrateMakeCommand::class,
        ModelMakeCommand::class,
        RequestMakeCommand::class,
        ResourceMakeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
