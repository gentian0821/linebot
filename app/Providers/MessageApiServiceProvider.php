<?php

namespace App\Providers;

use App\Services\MessageApiService;
use Illuminate\Support\ServiceProvider;

class MessageApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MessageApi', function($app) {
            return new MessageApiService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
