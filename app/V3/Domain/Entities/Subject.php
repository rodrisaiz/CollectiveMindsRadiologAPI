<?php

namespace App\V3\Domain\Entities;

class Subject
{
    private ?int $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private \DateTime $created_at;
    private \DateTime $updated_at;
    private bool $wasRecentlyCreated = false;
    private array $projects = [];

    public function __construct(?int $id, string $email, string $firstName, string $lastName, \DateTime $created_at, \DateTime $updated_at)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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

    public function setProjects(array $projects): void
    {
        $this->projects = $projects;
    }

    public function getProjects(): array
    {
        return $this->projects;
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
