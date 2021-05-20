<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_creation_form_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('transaction.create'));

        $response->assertStatus(200);
    }

    public function test_user_can_add_transaction_to_wallet(): void
    {
        $user = User::factory()->create();
        $source = Wallet::factory()->for($user)->create();
        $target = Wallet::factory()->create();

        $response = $this->actingAs($user)->post(
            route('transaction.store'),
            [
                'id' => 0,
                'source' => $source->id,
                'target' => $target->id,
                'amount' => 500,
                'currency' => 'EUR',
                'notes' => 'Test transaction',
            ]
        );

        $this->assertDatabaseHas(
            'transactions',
            [
                'wallet_id' => $source->id,
                'other_wallet_id' => $target->id,
                'debits' => null,
                'credits' => 500,
                'currency' => 'EUR',
                'notes' => 'Test transaction',
                'fraudulent' => false,
            ]
        );
        $this->assertDatabaseHas(
            'transactions',
            [
                'wallet_id' => $target->id,
                'other_wallet_id' => $source->id,
                'debits' => 500,
                'credits' => null,
                'currency' => 'EUR',
                'notes' => 'Test transaction',
                'fraudulent' => false,
            ]
        );
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_user_can_list_transactions_in_wallet(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();
        Transaction::factory()->for($wallet)->count(5)->create();

        $response = $this->actingAs($user)->get(route('wallet.show', $wallet));

        $response->assertStatus(200);

        $transactions = $response->viewData('page')['props']['wallet']['transactions'];

        self::assertCount(5, $transactions);
    }

    public function test_user_can_delete_transaction_from_wallet(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();
        $transaction = Transaction::factory()->for($wallet)->create();

        $response = $this->actingAs($user)->delete(route('transaction.destroy', $transaction));

        $response->assertRedirect(route('wallet.show', $wallet));
    }

    public function test_user_can_mark_transaction_as_fraudulent(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();
        $transaction = Transaction::factory()->for($wallet)->create();

        $response = $this->actingAs($user)->post(route('transaction.mark', $transaction));

        $this->assertDatabaseHas(
            'transactions',
            [
                'id' => $transaction->id,
                'fraudulent' => true,
            ]
        );
        $response->assertRedirect(route('wallet.show', $wallet));
    }

    public function test_user_can_see_total_in_out_wallet_transactions(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();
        $transactions = Transaction::factory()->count(5)->for($wallet)->create();
        $transactions->add(Transaction::factory(['credits' => null, 'debits' => 256])->for($wallet)->create());

        $response = $this->actingAs($user)->get(route('wallet.show', $wallet));
        $walletReceived = $response->viewData('page')['props']['wallet'];

        self::assertEquals(256, $walletReceived['total_in']->getAmount());
        self::assertEquals($transactions->sum(fn($x) => $x->credits), $walletReceived['total_out']->getAmount());
    }
}
