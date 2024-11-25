<?php

namespace App\V2\Listeners;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Webhook;

class BaseListener
{
    public function handle($event)
    {
        $entity = $event->entity;
        $action = $event->action;

        $type = match(get_class($event)) {
            'App\V2\Events\ProjectEvent' => 'projectV2',
            'App\V2\Events\SubjectEvent' => 'subjectV2',
            default => null,
        };
        if (!$type) {
            Log::error('Tipo de evento no reconocido.');
            return;
        }

        $webhook = Webhook::where('type', $type)->first();
        if (!$webhook || !$webhook->url) {
            Log::error("No se encontró un webhook configurado para el tipo \"{$type}\".");
            return;
        }

        Http::post($webhook->url, [
            'action' => $action,
            'id' => $entity->id,
        ]);
    }
}
