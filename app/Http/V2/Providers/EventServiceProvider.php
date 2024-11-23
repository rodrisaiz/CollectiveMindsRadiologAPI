<?php

namespace App\Http\V2\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Http\V2\Events\SubjectEvent::class => [
            \App\Http\V2\Listeners\SendSubjectWebhook::class,
        ],
        \App\Http\V2\Events\ProjectEvent::class => [
            \App\Http\V2\Listeners\SendProjectWebhook::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
