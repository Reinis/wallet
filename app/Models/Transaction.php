<?php

namespace App\Models;

use Cknow\Money\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransaction
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'wallet_id',
        'other',
        'debit',
        'credit',
        'currency',
        'fraudulent',
        'notes',
    ];

    protected $casts = [
        'debit' => MoneyCast::class . ':currency',
        'credit' => MoneyCast::class . ':currency',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
