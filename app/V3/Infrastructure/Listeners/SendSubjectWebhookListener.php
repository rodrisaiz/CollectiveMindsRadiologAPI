<?php

namespace App\V3\Infrastructure\Listeners;

use App\V3\Domain\Contracts\WebhookInterface;

class SendSubjectWebhookListener
{
    private $webhookService;

    public function __construct(WebhookInterface $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle($event)
    {
        $this->webhookService->send('subjectV3', $event->action, $event->entity->id);
    }
}
