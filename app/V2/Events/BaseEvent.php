<?php

namespace App\V2\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BaseEvent
{
    use Dispatchable, SerializesModels;

    public $entity;
    public $action;

    public function __construct($entity, $action)
    {
        $this->entity = $entity;
        $this->action = $action;
    }
}
