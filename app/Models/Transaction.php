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
        'debit',
        'credit',
        'currency',
        'notes',
    ];

    protected $casts = [
        'amount' => MoneyCast::class . ':currency',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
