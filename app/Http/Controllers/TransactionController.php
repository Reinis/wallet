<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display the transaction creation form.
     */
    public function create()
    {
        $wallets = Wallet::whereUserId(Auth::id())->get(['id','name']);

        return Inertia::render('Transaction/Edit', compact('wallets'));
    }

    /**
     * Handle an incoming transaction request.
     */
    public function save(Request $request)
    {
        $request->validate(
            [
                'source' => Rule::exists('wallets', 'id')->where(function($query){
                    return $query->where('user_id', '=', Auth::id());
                }),
                'target' => 'required|string|max:255',
                'amount' => 'required|integer',
                'currency' => 'required|string|max:3|regex:/^EUR$/',
                'notes' => 'nullable|string|max:255',
            ]
        );

        Transaction::create(
            [
                'wallet_id' => $request->source,
                'other' => $request->target,
                'credit' => $request->amount,
                'currency' => $request->currency,
                'notes' => $request->notes ?? '',
            ]
        );

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
