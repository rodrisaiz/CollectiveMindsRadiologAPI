<?php

namespace App\Http\V2\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\V2\Events\SubjectEvent;
use Illuminate\Support\Facades\Http;
use App\Models\Webhook;
use Illuminate\Support\Facades\Log;

class SendSubjectWebhook
{

    public function handle(SubjectEvent $event)
    {
        $subject = $event->subject;
        $action = $event->action;

         $webhook = Webhook::where('type', 'subjectV2')->first();

         if (!$webhook || !$webhook->url) {
             Log::error('No se encontró un webhook configurado para el tipo "subject".');
             return;
         }

        Http::post($webhook->url, [
            'action' => $action, 
            'id' => $subject->id,
        ]);
    }
}
