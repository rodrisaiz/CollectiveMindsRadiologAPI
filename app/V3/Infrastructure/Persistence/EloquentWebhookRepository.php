<?php

namespace App\V3\Infrastructure\Persistence;

use App\V3\Domain\Entities\Webhook;
use App\V3\Domain\Repositories\WebhookRepositoryInterface;
use App\Models\Webhook as Eloquentwebhook;
use Illuminate\Support\Facades\Log;

class EloquentWebhookRepository implements WebhookRepositoryInterface
{

    public function all(): array
    {
        $webhookModels = EloquentWebhook::all();

        $lastValue = $webhookModels->map(fn ($model) => $this->toDomain($model))->toArray();

        return $lastValue;
    }

    public function findById(int $id): ?Webhook
    {
        $webhookModel = EloquentWebhook::find($id);
        return $webhookModel ? $this->toDomain($webhookModel) : null;
    }

    public function findByType(string $type): ?Webhook
    {
        $webhookModel = EloquentWebhook::where('type', $type)->first();
        return $webhookModel ? $this->toDomain($webhookModel) : null;
    }

    public function save(Webhook $webhook): void
    {
        try {
            $eloquentWebhook = EloquentWebhook::updateOrCreate(
                ['type' => $webhook->getType()],
                [
                    'url' => $webhook->getUrl(),
                ]
            );
    
            $webhook->setWasRecentlyCreated($eloquentWebhook->wasRecentlyCreated);
    
        } catch (\Exception $e) {
            Log::error('Error saving webhook', [
                'name' => $webhook->getType(),
                'error' => $e->getMessage()
            ]);
    
            throw $e;
        }

    }

    public function delete(int $id): void
    {
        EloquentWebhook::find($id)->delete();
    }


    private function toDomain(EloquentWebhook $model): Webhook
    {
        return new webhook($model->id, $model->type, $model->url);
    }
}
