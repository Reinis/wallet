<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLogout(): void
    {
        $user = User::factory()->create();

        $this->browse(
            function (Browser $browser) use ($user) {
                $browser->visit('/dashboard')
                    ->waitFor('form button[type=submit]')
                    ->type('form #email', $user->email)
                    ->type('form #password', 'password')
                    ->click('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertAuthenticated()
                    ->visit('/dashboard')
                    ->waitForTextIn('h2', 'Dashboard')
                    ->assertSeeIn('@profile-button', $user->name)
                    ->press('@profile-button')
                    ->waitForTextIn('@logout-button', 'Log Out')
                    ->press('Log Out')
                    ->assertGuest();
            }
        );
    }
}
