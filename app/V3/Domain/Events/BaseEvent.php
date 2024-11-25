<?php

namespace App\V3\Domain\Events;

class BaseEvent
{
    public $entity;
    public $action;

    public function __construct($entity, $action)
    {
        $this->entity = $entity;
        $this->action = $action;
    }
}
