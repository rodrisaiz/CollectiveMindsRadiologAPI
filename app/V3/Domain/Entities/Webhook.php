<?php

namespace App\V3\Domain\Entities;

class Webhook
{
    private ?int $id;
    private string $type;
    private string $url;
    private bool $wasRecentlyCreated = false;


    public function __construct(?int $id, string $type, string $url)
    {
        $this->id = $id;
        $this->type = $type;
        $this->url = $url;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setWasRecentlyCreated(bool $wasRecentlyCreated): void
    {
        $this->wasRecentlyCreated = $wasRecentlyCreated;
    }

    public function wasRecentlyCreated(): bool
    {
        return $this->wasRecentlyCreated;
    }
}
