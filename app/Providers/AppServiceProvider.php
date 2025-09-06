<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\URL;
use App\Observers\PropertyObserver;
use App\Models\Property;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Property::observe(PropertyObserver::class);




        if (!defined('OMISE_PUBLIC_KEY')) {
            define('OMISE_PUBLIC_KEY', config('omise.public_key'));
        }

        if (!defined('OMISE_SECRET_KEY')) {
            define('OMISE_SECRET_KEY', config('omise.secret_key'));
        }

        if (!defined('OMISE_API_VERSION')) {
            define('OMISE_API_VERSION', config('omise.api_version'));
        }

        if (app()->runningInConsole()) {
            return; // Skip during artisan commands like migrations
        }

        if (Schema::hasTable('smtp_settings')) {
            $smtpsetting = SmtpSetting::first();
            if ($smtpsetting) {
                Config::set('mail.mailers.smtp.transport', 'smtp');
                Config::set('mail.mailers.smtp.host', $smtpsetting->host);
                Config::set('mail.mailers.smtp.port', $smtpsetting->port);
                Config::set('mail.mailers.smtp.username', $smtpsetting->username);
                Config::set('mail.mailers.smtp.password', $smtpsetting->password);
                Config::set('mail.mailers.smtp.encryption', $smtpsetting->encryption);
                Config::set('mail.from.address', $smtpsetting->from_address);
                Config::set('mail.from.name', $smtpsetting->from_name ?? 'baanlist');
            }
        }
    }
}