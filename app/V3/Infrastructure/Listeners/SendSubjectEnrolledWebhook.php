<?php

namespace App\V3\Infrastructure\Listeners;

use App\V3\Domain\Contracts\WebhookInterface;
use App\V3\Domain\Events\SubjectEnrolledInProjectEvent;

class SendSubjectEnrolledWebhook
{
    private WebhookInterface $webhookService;

    public function __construct(WebhookInterface $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(SubjectEnrolledInProjectEvent $event)
    {
        $this->webhookService->send('subjectV3', 'Subject enrolled in project', $event->subject->getId());
    }
}
