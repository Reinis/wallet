<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WalletCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testWalletCreation()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->assertAuthenticated()
                ->visit('/dashboard')
                ->waitForTextIn('h2', 'Dashboard')
                ->assertSeeLink('New Wallet')
                ->clickLink('New Wallet')
                ->waitFor('form button[type=submit]')
                ->type('form #name', 'Cool Wallet')
                ->type('form #description', 'Has lots of money')
                ->click('form button[type=submit]')
                ->waitForLocation('/dashboard')
                ->assertSeeIn('.card', 'Cool Wallet');
        });
    }
}
