<?php
namespace App\V3\Application\UseCases\Webhook;

use App\V3\Domain\Entities\Webhook;
use App\V3\Domain\Repositories\WebhookRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CreateWebhook
{
    private WebhookRepositoryInterface $repository;

    public function __construct(WebhookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $type, string $url): Webhook
    {
    
        $Webhook = new Webhook(
            null,
            $type,
            $url
        );

        $this->repository->save($Webhook);

        $savedWebhook = $this->repository->findByType($type);
        $Webhook->setId($savedWebhook->getId());

        return $Webhook;
    }
}
