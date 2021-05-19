<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'operation_id' => null,
            'wallet_id' => Wallet::factory(),
            'other' => null,
            'other_wallet_id' => Wallet::factory(),
            'debits' => null,
            'credits' => $this->faker->numberBetween(1),
            'currency' => 'EUR',
            'fraudulent' => false,
            'notes' => $this->faker->sentence,
        ];
    }
}
