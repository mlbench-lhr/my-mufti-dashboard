<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DatabaseSwitcher
{
    public function handle($request, Closure $next)
    {
        if ($request->is('api/testing/*')) {
            Config::set('database.default', 'testing_db');
            Log::info('Database switched to: testing_db');
        } else {
            Config::set('database.default', 'mysql');
            Log::info('Database switched to: mysql');
        }
        return $next($request);
    }
}
