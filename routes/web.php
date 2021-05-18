<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Foundation\Application;
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

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('wallet')->name('wallet.')->where(['wallet' => '[0-9]+'])->group(
            function () {
                Route::get('/create', [WalletController::class, 'create'])->name('create');
                Route::post('/create', [WalletController::class, 'store'])->name('store');
                Route::get('/{wallet}/edit', [WalletController::class, 'edit'])->name('edit');
                Route::delete('/{wallet}', [WalletController::class, 'destroy'])->name('destroy');
                Route::get('/{wallet}', [WalletController::class, 'show'])->name('show');
            }
        );

        Route::prefix('transaction')->name('transaction.')->where(['transaction' => '[0-9]+'])->group(
            function () {
                Route::get('/create', [TransactionController::class, 'create'])->name('create');
                Route::post('/create', [TransactionController::class, 'store'])->name('store');
                Route::post('/{transaction}/mark', [TransactionController::class, 'mark'])->name('mark');
                Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
            }
        );
    }
);
