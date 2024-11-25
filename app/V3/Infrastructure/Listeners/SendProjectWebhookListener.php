<?php

namespace App\V3\Infrastructure\Listeners;

use App\V3\Domain\Contracts\WebhookInterface;

class SendProjectWebhookListener
{
    private $webhookService;

    public function __construct(WebhookInterface $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle($event)
    {
        $this->webhookService->send('projectV3', $event->action, $event->entity->id);
    }
}
