<?php

declare(strict_types=1);

namespace App\Models\Dto;

class StoreWalletServiceRequest
{
    private string $name;
    private string $description;
    private int $id;

    public function __construct(string $name, string $description, int $id = 0)
    {
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
