<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperWallet
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAttribute(): Money
    {
        $debit = $this->getTotalInAttribute();
        $credit = $this->getTotalOutAttribute();

        return $debit->subtract($credit);
    }

    public function getTotalInAttribute(): Money
    {
        return $this->transactions->reduce(
            function ($carry, $transaction) {
                if (!$transaction->debit) {
                    return $carry;
                }

                return $carry->add($transaction->debit);
            },
            Money::EUR(0)
        );
    }

    public function getTotalOutAttribute(): Money
    {
        return $this->transactions->reduce(
            function ($carry, $transaction) {
                if (!$transaction->credit) {
                    return $carry;
                }

                return $carry->add($transaction->credit);
            },
            Money::EUR(0)
        );
    }
}
