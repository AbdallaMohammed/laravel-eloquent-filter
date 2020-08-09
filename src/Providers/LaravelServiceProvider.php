<?php

namespace LaravelEloquentFilter\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelEloquentFilter\Commands\MakeFilterCommand;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-eloquent-filter.php' => config_path('laravel-eloquent-filter.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(MakeFilterCommand::class);
    }
}