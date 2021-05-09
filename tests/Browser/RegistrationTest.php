<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testRegister(): void
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                    ->assertSee('Laravel')
                    ->assertSeeLink('Log in')
                    ->assertSeeLink('Register')
                    ->clickLink('Register')
                    ->waitFor('form button[type=submit]')
                    ->type('form #name', 'Test User')
                    ->type('form #email', 'test@example.com')
                    ->type('form #password', 'password')
                    ->type('form #password_confirmation', 'password')
                    ->click('form button[type=submit]')
                    ->waitForLocation('/dashboard')
                    ->assertAuthenticated();
            }
        );
    }
}
