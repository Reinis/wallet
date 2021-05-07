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
     * Handle an incoming wallet creation request.
     */
    public function save(Request $request)
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

    public function delete(Wallet $wallet)
    {
        $wallet->delete();

        return redirect(RouteServiceProvider::HOME);
    }
}
