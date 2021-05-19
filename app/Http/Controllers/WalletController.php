<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWalletRequest;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use App\Services\CreateWalletService;
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
    public function store(CreateWalletRequest $request, CreateWalletService $createWalletService)
    {
        $createWalletService->store($request);

        session()->flash('message', "Wallet saved!");

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
