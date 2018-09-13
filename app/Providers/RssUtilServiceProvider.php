<?php

namespace App\Providers;

use Illuminate\Foundation\Application;

use Illuminate\Support\ServiceProvider;
use App\Services\RssUtilService;

class RssUtilServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('rssutil', function(Application $app){
            return new RssUtilService();
        });
    }
}
