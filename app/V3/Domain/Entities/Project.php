<?php

namespace App\V3\Domain\Entities;

class Project
{
    private ?int $id;
    private string $name;
    private string $description;
    private \DateTime $created_at;
    private \DateTime $updated_at;
    private bool $wasRecentlyCreated = false;

    public function __construct(?int $id, string $name, string $description, \DateTime $created_at, \DateTime $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = $created_at ?: new \DateTime();
        $this->updated_at = $updated_at ?: new \DateTime();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): void
{
    $this->updated_at = $updated_at;
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
