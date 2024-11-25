<?php

namespace App\V3\Domain\Contracts;

interface EventInterface
{
    public function dispatch($entity, $action): void;
}
