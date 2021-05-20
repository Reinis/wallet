<?php

declare(strict_types=1);

namespace App\Models\Dto;

class StoreTransactionServiceRequest
{
    private int $source;
    private int $target;
    private int $amount;
    private string $currency;
    private string $notes;

    public function __construct(
        int $source,
        int $target,
        int $amount,
        string $currency = 'EUR',
        string $notes = ''
    )
    {
        $this->source = $source;
        $this->target = $target;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->notes = $notes;
    }

    public function getSource(): int
    {
        return $this->source;
    }

    public function getTarget(): int
    {
        return $this->target;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }
}
