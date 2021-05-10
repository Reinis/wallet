<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WalletListTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testWalletList(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->assertAuthenticated()
                ->visitRoute('wallet.create')
                ->waitFor('form button[type=submit]')
                ->type('form #name', 'Cool Wallet')
                ->type('form #description', 'Has lots of money')
                ->press('form button[type=submit]')
                ->waitForLocation('/dashboard')
                ->visitRoute('wallet.create')
                ->waitFor('form button[type=submit]')
                ->type('form #name', 'Hot Wallet')
                ->type('form #description', 'Has no money')
                ->press('form button[type=submit]')
                ->waitForLocation('/dashboard')
                ->assertSee('Cool Wallet')
                ->assertSee('Hot Wallet');
        });
    }
}
