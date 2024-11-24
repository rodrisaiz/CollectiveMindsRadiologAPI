<?php

namespace App\V3\Domain\Entities;

class Subject
{
    private ?int $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private bool $wasRecentlyCreated = false;

    public function __construct(?int $id, string $email, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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

    public function setWasRecentlyCreated(bool $wasRecentlyCreated): void
    {
        $this->wasRecentlyCreated = $wasRecentlyCreated;
    }

    public function wasRecentlyCreated(): bool
    {
        return $this->wasRecentlyCreated;
    }
}
