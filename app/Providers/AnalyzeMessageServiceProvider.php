<?php

namespace App\Providers;

use App\Services\AnalyzeMessageService;
use Illuminate\Support\ServiceProvider;

class AnalyzeMessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AnalyzeMessage', function($app) {
            return new AnalyzeMessageService();
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
