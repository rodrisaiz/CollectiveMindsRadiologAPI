<?php

namespace App\V3\Application\UseCases\Webhook;

use App\V3\Domain\Entities\Webhook;
use App\V3\Domain\Repositories\WebhookRepositoryInterface;
use Illuminate\Support\Facades\Log;

class allWebhook
{
    private WebhookRepositoryInterface $repository;

    public function __construct(WebhookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(): array
    {
        return $this->repository->all();
        
    }
}
