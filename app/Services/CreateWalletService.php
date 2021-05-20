<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Dto\StoreWalletServiceRequest;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class CreateWalletService
{
    public function store(StoreWalletServiceRequest $wallet): void
    {
        Wallet::upsert(
            [
                'id' => $wallet->getId(),
                'name' => $wallet->getName(),
                'user_id' => Auth::id(),
                'description' => $wallet->getDescription(),
            ],
            ['id'],
            ['name', 'description'],
        );
    }
}
