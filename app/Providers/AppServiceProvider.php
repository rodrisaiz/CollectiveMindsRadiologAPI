<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\V2\Providers\EventServiceProvider as V2EventServiceProvider;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use App\V3\Infrastructure\Persistence\EloquentSubjectRepository;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use App\V3\Infrastructure\Persistence\EloquentProjectRepository;
use App\V3\Domain\Repositories\SubjectsInProjectsRepositoryInterface;
use App\V3\Infrastructure\Persistence\EloquentSubjectsInProjectsRepository;
use App\V3\Domain\Repositories\WebhookRepositoryInterface;
use App\V3\Infrastructure\Persistence\EloquentWebhookRepository;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Infrastructure\Services\LaravelEventService;
use App\V3\Domain\Contracts\WebhookInterface;
use App\V3\Infrastructure\Services\WebhookService;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(V2EventServiceProvider::class);
        $this->app->bind(SubjectRepositoryInterface::class, EloquentSubjectRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(SubjectsInProjectsRepositoryInterface::class, EloquentSubjectsInProjectsRepository::class);
        $this->app->bind(WebhookRepositoryInterface::class, EloquentWebhookRepository::class);
        $this->app->bind(EventInterface::class, LaravelEventService::class);
        $this->app->bind(WebhookInterface::class, WebhookService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
