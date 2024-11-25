<?php

namespace App\V3\Domain\Repositories;

use App\V3\Domain\Entities\Webhook;

interface WebhookRepositoryInterface
{

    public function all(): array;
    public function findById(int $id): ?Webhook;
    public function findByType(string $Type): ?Webhook;
    public function save(Webhook $webhook): void;
    public function delete(int $id): void;
}
