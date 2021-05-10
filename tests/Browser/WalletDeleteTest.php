<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WalletDeleteTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testWalletDelete(): void
    {
        $user = User::factory()->create();

        $this->browse(
            function (Browser $browser) use ($user) {
                $browser->loginAs($user)
                    ->assertAuthenticated()
                    ->visitRoute('wallet.create')
                    ->waitFor('form button[type=submit]')
                    ->type('form #name', 'Cool Wallet')
                    ->type('form #description', 'Has lots of money')
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSeeIn('.card', 'Cool Wallet')
                    ->mouseover('.card')
                    ->assertSeeLink('🖊')
                    ->click('.card-button')
                    ->waitFor('form button[type=submit]')
                    ->press('@delete-button')
                    ->waitForLocation('/dashboard')
                    ->assertDontSee('Cool Wallet');
            }
        );
    }
}
