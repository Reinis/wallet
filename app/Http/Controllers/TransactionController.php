<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display the transaction creation form.
     */
    public function create()
    {
        $wallets = Wallet::whereUserId(Auth::id())->get(['id', 'name']);

        $allWallets = array_replace(
            [],
            ...array_map(
                static fn(array $wallet): array => [$wallet['id'] => $wallet['name']],
                Wallet::get(['id', 'name'])->toArray()
            )
        );

        return Inertia::render('Transaction/Edit', compact('wallets', 'allWallets'));
    }

    /**
     * Handle an incoming transaction request.
     */
    public function store(CreateTransactionRequest $request)
    {
        $validated = $request->validated();
        $targetColumn = $validated['toWallet'] ? 'other_wallet_id' : 'other';
        $operationId = Transaction::max('operation_id') + 1;

        Transaction::create(
            [
                'operation_id' => $operationId,
                'wallet_id' => $validated['source'],
                $targetColumn => $validated['target'],
                'credits' => $validated['amount'],
                'currency' => $validated['currency'],
                'notes' => $validated['notes'] ?? '',
            ]
        );

        if ($validated['toWallet']) {
            Transaction::create(
                [
                    'operation_id' => $operationId,
                    'wallet_id' => $validated['target'],
                    'other_wallet_id' => $validated['source'],
                    'debits' => $validated['amount'],
                    'currency' => $validated['currency'],
                    'notes' => $validated['notes'] ?? '',
                ]
            );
        }

        session()->flash('message', "Transaction complete!");

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Toggle the fraudulent status of a transaction.
     */
    public function mark(Transaction $transaction)
    {
        $transaction->fraudulent = !$transaction->fraudulent;
        $transaction->saveOrFail();

        return Redirect::route('wallet.show', $transaction->wallet);
    }

    /**
     * Delete a transaction.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return Redirect::route('wallet.show', $transaction->wallet);
    }
}
