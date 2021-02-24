<?php

namespace App\Providers;

use App\Http\Presenters\Schedule\ScheduleSendPresenter;
use Illuminate\Support\ServiceProvider;
use packages\UseCase\Schedule\Send\ScheduleSendPresenterInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerForInMemory();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function registerForInMemory(){
        $this->app->singleton(
            \packages\Infrastructure\Schedule\ScheduleRepositoryInterface::class,
            \packages\Infrastructure\Schedule\ScheduleRepository::class
        );

        $this->app->bind(
            ScheduleSendPresenterInterface::class,
            ScheduleSendPresenter::class
        );

        $this->app->singleton(
            \packages\Infrastructure\Message\TaskRepositoryInterface::class,
            \packages\Infrastructure\Message\TaskRepository::class
        );

        $this->app->singleton(
            \packages\Infrastructure\Weather\WeatherRepositoryInterface::class,
            \packages\Infrastructure\Weather\WeatherRepository::class
        );

        $this->app->singleton(
            \packages\Infrastructure\Weather\OpenWeatherRepositoryInterface::class,
            \packages\Infrastructure\Weather\OpenWeatherRepository::class
        );

        $this->app->singleton(
            \packages\Infrastructure\Notify\NotifyRepositoryInterface::class,
            \packages\Infrastructure\Notify\NotifyRepository::class
        );
    }
}
