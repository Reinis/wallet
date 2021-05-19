<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Money\Money;

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
        'other_wallet_id',
        'debits',
        'credits',
        'currency',
        'fraudulent',
        'notes',
    ];

    public function getCreditAttribute(): Money
    {
        return new Money($this->credits, new Currency($this->currency));
    }

    public function setCreditAttribute(Money $value): void
    {
        $this->credits = $value->getAmount();
        $this->currency = $value->getCurrency()->getCode();
    }

    public function getDebitAttribute(): Money
    {
        return new Money($this->debits, new Currency($this->currency));
    }

    public function setDebitAttribute(Money $value): void
    {
        $this->debits = $value->getAmount();
        $this->currency = $value->getCurrency()->getCode();
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
