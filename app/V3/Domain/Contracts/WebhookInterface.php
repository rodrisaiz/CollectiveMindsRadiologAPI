<?php

namespace App\V3\Domain\Contracts;

interface WebhookInterface
{
    public function send(string $type, string $action, int $entityId): void;
}
