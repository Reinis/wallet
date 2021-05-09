<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionPostRequest;
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

        $allWallets = array_combine(...array_map(null, ...Wallet::get(['id', 'name'])->toArray()));

        return Inertia::render('Transaction/Edit', compact('wallets', 'allWallets'));
    }

    /**
     * Handle an incoming transaction request.
     */
    public function save(TransactionPostRequest $request)
    {
        $validated = $request->validated();
        $targetColumn = $validated['toWallet'] ? 'other_wallet_id' : 'other';

        Transaction::create(
            [
                'wallet_id' => $validated['source'],
                $targetColumn => $validated['target'],
                'credit' => $validated['amount'],
                'currency' => $validated['currency'],
                'notes' => $validated['notes'] ?? '',
            ]
        );

        if ($validated['toWallet']) {
            Transaction::create(
                [
                    'wallet_id' => $validated['target'],
                    'other_wallet_id' => $validated['source'],
                    'debit' => $validated['amount'],
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
    public function delete(Transaction $transaction)
    {
        $transaction->delete();

        return Redirect::route('wallet.show', $transaction->wallet);
    }
}
