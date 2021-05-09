<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLogin()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->waitFor('form button[type=submit]')
                ->type('form #email', $user->email)
                ->type('form #password', 'password')
                ->click('form button[type=submit]')
                ->waitForLocation('/dashboard')
                ->assertAuthenticated();
        });
    }
}
