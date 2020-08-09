<?php

namespace LaravelEloquentFilter\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelEloquentFilter\Commands\MakeFilterCommand;

class LumenServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(MakeEloquentFilter::class);
    }
}