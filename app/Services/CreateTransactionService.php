<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Dto\StoreTransactionServiceRequest;
use App\Models\Transaction;

class CreateTransactionService
{
    public function store(StoreTransactionServiceRequest $transaction): void
    {
        $operationId = Transaction::max('operation_id') + 1;

        Transaction::create(
            [
                'operation_id' => $operationId,
                'wallet_id' => $transaction->getSource(),
                'other_wallet_id' => $transaction->getTarget(),
                'credits' => $transaction->getAmount(),
                'currency' => $transaction->getCurrency(),
                'notes' => $transaction->getNotes(),
            ]
        );

        Transaction::create(
            [
                'operation_id' => $operationId,
                'wallet_id' => $transaction->getTarget(),
                'other_wallet_id' => $transaction->getSource(),
                'debits' => $transaction->getAmount(),
                'currency' => $transaction->getCurrency(),
                'notes' => $transaction->getNotes(),
            ]
        );
    }
}
