<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

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
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        if (Request::is('testing/*') || Request::is('api/testing/*')) {
            Config::set('database.default', 'testing_db');
            Log::info('Database switched to: testing_db (via AppServiceProvider)');
        } else {
            Config::set('database.default', 'mysql');
            Log::info('Database switched to: mysql (via AppServiceProvider)');
        }

    }
}
