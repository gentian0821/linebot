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
            \packages\Domain\Domain\Schedule\ScheduleRepositoryInterface::class,
            \packages\Infrastructure\Schedule\ScheduleRepository::class
        );

        $this->app->bind(
            \packages\UseCase\Schedule\Send\ScheduleSendUseCaseInterface::class,
            \packages\Domain\Application\Schedule\ScheduleSendInteractor::class
        );
        $this->app->bind(
            ScheduleSendPresenterInterface::class,
            ScheduleSendPresenter::class
        );
    }
}
