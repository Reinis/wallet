<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use App\Services\CreateTransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TransactionsController extends Controller
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
                static fn(array $wallet): array => [$wallet['id'] => "{$wallet['user']['name']}: {$wallet['name']}"],
                Wallet::with('user')->get()->toArray()
            )
        );

        return Inertia::render('Transaction/Edit', compact('wallets', 'allWallets'));
    }

    /**
     * Handle an incoming transaction request.
     */
    public function store(CreateTransactionRequest $request, CreateTransactionService $createTransactionService)
    {
        $createTransactionService->store($request->validated());

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
