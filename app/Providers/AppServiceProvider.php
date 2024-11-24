<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\V2\Providers\EventServiceProvider as V2EventServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(V2EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
