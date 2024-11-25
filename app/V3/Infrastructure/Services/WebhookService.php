<?php

namespace App\V3\Infrastructure\Services;

use App\V3\Domain\Contracts\WebhookInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Webhook;

class WebhookService implements WebhookInterface
{
    public function send(string $type, string $action, int $entityId): void
    {
        $webhook = Webhook::where('type', $type)->first();

        if (!$webhook || !$webhook->url) {
            Log::error("No se encontró un webhook configurado para el tipo \"{$type}\".");
            return;
        }

        Http::post($webhook->url, [
            'action' => $action,
            'id' => $entityId,
        ]);
    }
}
