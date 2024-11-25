<?php

namespace App\V3\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\V3\Domain\Events\BaseEvent;
use App\V3\Infrastructure\Listeners\SendSubjectWebhookListener;
use App\V3\Domain\Events\SubjectEnrolledInProjectEvent;
use App\V3\Infrastructure\Listeners\SendSubjectEnrolledWebhook;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BaseEvent::class => [
            SendSubjectWebhookListener::class,
        ],
    ];
}

