<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\CreateWalletRequest;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class CreateWalletService
{
    public function store(CreateWalletRequest $request): void
    {
        $wallet = $request->validated();

        Wallet::upsert(
            [
                'id' => $wallet['id'],
                'name' => $wallet['name'],
                'user_id' => Auth::id(),
                'description' => $wallet['description'],
            ],
            ['id'],
            ['name', 'description'],
        );
    }
}
