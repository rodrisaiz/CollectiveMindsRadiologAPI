<?php

namespace App\V3\Application\UseCases\Webhook;

use App\V3\Domain\Entities\Webhook;
use App\V3\Domain\Repositories\WebhookRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class UpdateWebhook
{
    private WebhookRepositoryInterface $repository;

    public function __construct(WebhookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id, array $data): ?Webhook
    {
        $Webhook = $this->repository->findById($id);
        if (isset($data['type'])) {
            $Webhook->setType($data['type']);
        }

        if (isset($data['url'])) {
            $Webhook->setUrl($data['url']);
        }

        $this->repository->save($Webhook);

        return $Webhook;
    }

}
