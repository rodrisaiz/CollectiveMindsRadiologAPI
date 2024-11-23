<?php

namespace App\Http\V2\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\V2\Events\ProjectEvent;
use Illuminate\Support\Facades\Http;
use App\Models\Webhook;
use Illuminate\Support\Facades\Log;

class SendProjectWebhook
{

    public function handle(ProjectEvent $event)
    {
        $project = $event->project;
        $action = $event->action;

         $webhook = Webhook::where('type', 'projectV2')->first();

         if (!$webhook || !$webhook->url) {
             Log::error('No se encontró un webhook configurado para el tipo "subject".');
             return;
         }

        Http::post($webhook->url, [
            'action' => $action, 
            'id' => $project->id,
        ]);
    }
}
