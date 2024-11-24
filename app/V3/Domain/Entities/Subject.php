<?php

namespace App\V3\Domain\Entities;

class Subject
{
    private string $email;
    private string $firstName;
    private string $lastName;
    private bool $wasRecentlyCreated = false;

    public function __construct(string $email, string $firstName, string $lastName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
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
