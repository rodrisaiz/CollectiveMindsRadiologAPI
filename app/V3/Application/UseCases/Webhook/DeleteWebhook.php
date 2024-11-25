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

    public function execute(int $id): ?Webhook
    {
        $Webhook = $this->repository->findById($id);
        if(is_null($Webhook)){
            return null;
        }
        $this->repository->delete($id);
    }

}
