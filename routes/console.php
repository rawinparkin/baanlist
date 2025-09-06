<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CleanUpOrphanedImages;


Artisan::command('cleanup:images', function () {
    Artisan::call(CleanUpOrphanedImages::class);
})->purpose('Remove unused temporary images');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
