<?php

namespace LaravelEloquentFilter;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use LaravelEloquentFilter\Commands\MakeFilterCommand;

class ServiceProvider extends LaravelServiceProvider
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