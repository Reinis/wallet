<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WalletController extends Controller
{
    /**
     * Display the wallet creation form.
     */
    public function create()
    {
        return Inertia::render('Wallet/Edit');
    }

    /**
     * Display wallet contents and transaction history.
     */
    public function show(Wallet $wallet)
    {
        $wallet->append(['total_in', 'total_out', 'balance']);

        return Inertia::render('Wallet/Show', compact('wallet'));
    }

    /**
     * Handle an incoming wallet creation request.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]
        );

        Wallet::upsert(
            [
                'id' => $request->id,
                'name' => $request->name,
                'user_id' => Auth::id(),
                'description' => $request->description ?? '',
            ],
            ['id'],
            ['name', 'description'],
        );

        return redirect(RouteServiceProvider::HOME);
    }

    public function edit(Wallet $wallet)
    {
        return Inertia::render('Wallet/Edit', compact('wallet'));
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect(RouteServiceProvider::HOME);
    }
}
