<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TransactionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testPrepare(): void
    {
        $user = User::factory()->create();
        $source = Wallet::factory(['name' => 'Cool Wallet', 'description' => 'Has lots of money'])
            ->for($user)
            ->create();
        $targets = Wallet::factory()->count(3)->for($user)->create();

        $this->browse(
            function (Browser $browser) use ($user, $targets) {
                $browser->loginAs($user)
                    ->assertAuthenticated()
                    ->visitRoute('dashboard')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Cool Wallet')
                    ->visitRoute('transaction.create')
                    ->waitFor('form button[type=submit]')
                    ->select('form #source', 1)
                    ->assertSelected('form #source', 1)
                    ->type('form #target', $targets[0]->id)
                    ->type('form #amount', 123)
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Transaction complete!')
                    ->assertSee('-€1.23')
                    ->visitRoute('transaction.create')
                    ->waitFor('form button[type=submit]')
                    ->select('form #source', 1)
                    ->assertSelected('form #source', 1)
                    ->type('form #target', $targets[1]->id)
                    ->type('form #amount', 321)
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Transaction complete!')
                    ->visitRoute('transaction.create')
                    ->waitFor('form button[type=submit]')
                    ->select('form #source', 1)
                    ->assertSelected('form #source', 1)
                    ->type('form #target', $targets[2]->id)
                    ->type('form #amount', 123)
                    ->press('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Transaction complete!')
                    ->click('.card')
                    ->waitFor('@delete-button')
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
