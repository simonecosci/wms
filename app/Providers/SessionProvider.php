<?php

namespace App\Providers;

use App\Extensions\MySessionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\ConnectionInterface;
use Session;
use Config;

class SessionProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(ConnectionInterface $connection)
    {
        Session::extend('my-database', function($app) use ($connection) {
            $table   = Config::get('session.table');
            $minutes = Config::get('session.lifetime');
            return new MySessionHandler($connection, $table, $minutes);
        });
    }
    /**
     * Register bindings in the container.
     *
     * @return void 
     */
    public function register()
    {
        //
    }
}