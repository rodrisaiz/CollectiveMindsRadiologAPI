<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\V2\Providers\EventServiceProvider as V2EventServiceProvider;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use App\V3\Infrastructure\Persistence\EloquentSubjectRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(V2EventServiceProvider::class);
        $this->app->bind(SubjectRepositoryInterface::class, EloquentSubjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
