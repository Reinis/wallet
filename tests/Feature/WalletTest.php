<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_creation_form_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('wallet.create'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_wallet(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            route('wallet.store'),
            [
                'id' => 0,
                'name' => 'Cool Wallet',
                'description' => 'Has lots of money',
            ]
        );

        $this->assertDatabaseHas(
            'wallets',
            [
                'name' => 'Cool Wallet',
                'user_id' => $user->id,
                'description' => 'Has lots of money',
            ]
        );
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_wallet_edit_form_can_be_displayed(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('wallet.edit', $wallet));

        $response->assertStatus(200);
    }

    public function test_user_can_edit_wallet(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();

        $response = $this->actingAs($user)->post(
            route('wallet.store'),
            [
                'id' => $wallet->id,
                'name' => 'Cool Wallet',
                'description' => 'Has lots of money',
            ]
        );

        $this->assertDatabaseHas(
            'wallets',
            [
                'id' => $wallet->id,
                'name' => 'Cool Wallet',
                'user_id' => $user->id,
                'description' => 'Has lots of money',
            ]
        );
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_user_can_list_wallets(): void
    {
        $user = User::factory()->create();
        Wallet::factory()
            ->count(3)
            ->for($user)
            ->create();

        $wallets = $this->actingAs($user)
            ->get(route('dashboard'))
            ->viewData('page')['props']['wallets'];

        self::assertCount(3, $wallets);
    }

    public function test_user_can_delete_wallet(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('wallet.destroy', $wallet));

        $this->assertDatabaseCount('wallets', 0);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
