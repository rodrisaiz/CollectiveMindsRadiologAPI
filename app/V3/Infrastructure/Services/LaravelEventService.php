<?php

namespace App\V3\Infrastructure\Services;

use App\V3\Domain\Contracts\EventInterface;
use Illuminate\Support\Facades\Event;

class LaravelEventService implements EventInterface
{
    public function dispatch($entity, $action): void
    {
        Event::dispatch(new \App\V3\Domain\Events\BaseEvent($entity, $action));
    }
}
