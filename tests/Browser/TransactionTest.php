<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TransactionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testPrepare(): void
    {
        $user = User::factory()->create();

        $this->browse(
            function (Browser $browser) use ($user) {
                $browser->loginAs($user)
                    ->assertAuthenticated()
                    ->visitRoute('wallet.create')
                    ->type('form #name', 'Cool Wallet')
                    ->type('form #description', 'Has lots of money')
                    ->press('@save-button')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Cool Wallet')
                    ->visitRoute('transaction.create')
                    ->waitFor('form button[type=submit]')
                    ->select('form #source', 1)
                    ->assertSelected('form #source', 1)
                    ->type('form #target', 'abc')
                    ->type('form #amount', 123)
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Transaction complete!')
                    ->assertSee('-€1.23')
                    ->visitRoute('transaction.create')
                    ->waitFor('form button[type=submit]')
                    ->select('form #source', 1)
                    ->assertSelected('form #source', 1)
                    ->type('form #target', 'def')
                    ->type('form #amount', 321)
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Transaction complete!')
                    ->visitRoute('transaction.create')
                    ->waitFor('form button[type=submit]')
                    ->select('form #source', 1)
                    ->assertSelected('form #source', 1)
                    ->type('form #target', 'ghi')
                    ->type('form #amount', 123)
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Transaction complete!')
                    ->click('.card')
                    ->press('@delete-button')
                    ->pause(1000)
                    ->assertDontSee('abc')
                    ->press('@mark-button')
                    ->pause(1000)
                    ->assertSee('❗')
                    ->assertSee('€0.00')
                    ->assertSee('€4.44');
            }
        );
    }
}
