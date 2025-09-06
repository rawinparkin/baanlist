<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Role;
use App\Http\Middleware\CheckUserCredit;
use App\Http\Middleware\EnsureUserIsOwner;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {


        $middleware->alias([
            'role' => Role::class,
            'check.credit' => CheckUserCredit::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Register your scheduled command here
        $schedule->command('images:cleanup-orphans')->dailyAt('01:00'); // or ->everySixHours()
        $schedule->command('plans:expire')->dailyAt('01:00');
        $schedule->command('properties:expire')->dailyAt('01:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
