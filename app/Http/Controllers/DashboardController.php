<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $wallets = Wallet::whereUserId(Auth::id())
            ->get(['id', 'name', 'description'])
            ->append(['balance']);

        return Inertia::render('Dashboard', compact('wallets'));
    }
}
