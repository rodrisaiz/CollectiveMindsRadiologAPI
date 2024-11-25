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
        /*
        $existingWebhook = $this->repository->findByType($type);

        if ($existingWebhook) {
            $webhook = Webhook::where('type', $type)->first();

            $existingWebhook->setType($type);
            $this->repository->save($existingWebhook);

            $existingWebhook->setWasRecentlyCreated($existingWebhook->wasRecentlyCreated);
            return $existingWebhook;
        }
*/
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
