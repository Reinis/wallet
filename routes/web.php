<?php

use App\Http\Controllers\WalletController;
use App\Models\Wallet;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    function () {
        return Inertia::render(
            'Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]
        );
    }
);

Route::get(
    '/dashboard',
    function () {
        $wallets = Wallet::whereUserId(Auth::id())->get(['id','name','description']);

        return Inertia::render('Dashboard', compact('wallets'));
    }
)->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get(
    '/wallet/create',
    [WalletController::class, 'create']
)->middleware(['auth', 'verified'])->name('wallet.create');

Route::post(
    '/wallet/create',
    [WalletController::class, 'save']
)->middleware(['auth', 'verified'])->name('wallet.save');

Route::get(
    '/wallet/{wallet}/edit',
    [WalletController::class, 'edit']
)->middleware(['auth', 'verified'])->name('wallet.edit');

Route::post(
    '/wallet/{wallet}/delete',
    [WalletController::class, 'delete']
)->middleware(['auth', 'verified'])->name('wallet.delete');
