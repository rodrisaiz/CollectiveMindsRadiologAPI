<?php

namespace App\Http\V2\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\V2\Events\SubjectEvent;
use Illuminate\Support\Facades\Http;

class SendSubjectWebhook
{

    public function handle(SubjectEvent $event)
    {
        $subject = $event->subject;

        $webhookUrl = config('services.webhook.subject');

        Http::post($webhookUrl, [
            'id' => $subject->id,
        ]);
    }
}
