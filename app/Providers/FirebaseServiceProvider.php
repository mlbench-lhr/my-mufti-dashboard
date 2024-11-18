<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Database::class, function ($app) {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('mymufti-ce4a6-firebase-adminsdk-ljivv-740bba83f5.json'))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

            return $factory->createDatabase();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // You can add any bootstrapping logic here if needed.
    }
}
