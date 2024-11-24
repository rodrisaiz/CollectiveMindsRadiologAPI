<?php

namespace App\V2\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\V2\Events\SubjectEvent::class => [
            \App\V2\Listeners\SendSubjectWebhook::class,
        ],
        \App\V2\Events\ProjectEvent::class => [
            \App\V2\Listeners\SendProjectWebhook::class,
        ],
    ];
    

    public function boot()
    {
        parent::boot();
    }
}
