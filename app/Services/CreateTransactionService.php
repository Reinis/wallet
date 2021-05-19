<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;

class CreateTransactionService
{
    public function store(CreateTransactionRequest $request): void
    {
        $transaction = $request->validated();
        $operationId = Transaction::max('operation_id') + 1;

        Transaction::create(
            [
                'operation_id' => $operationId,
                'wallet_id' => $transaction['source'],
                'other_wallet_id' => $transaction['target'],
                'credits' => $transaction['amount'],
                'currency' => $transaction['currency'],
                'notes' => $transaction['notes'] ?? '',
            ]
        );

        Transaction::create(
            [
                'operation_id' => $operationId,
                'wallet_id' => $transaction['target'],
                'other_wallet_id' => $transaction['source'],
                'debits' => $transaction['amount'],
                'currency' => $transaction['currency'],
                'notes' => $transaction['notes'] ?? '',
            ]
        );
    }
}
