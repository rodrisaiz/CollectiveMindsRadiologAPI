<?php

namespace App\V3\Application\UseCases\Webhook;

use App\V3\Domain\Entities\Webhook;
use App\V3\Domain\Repositories\WebhookRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class DeleteWebhook
{
    private WebhookRepositoryInterface $repository;

    public function __construct(WebhookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): void
    {
        $Webhook = $this->repository->delete($id);
    }

}
