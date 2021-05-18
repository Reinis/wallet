<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->post(
            '/login',
            [
                'email' => $user->email,
                'password' => 'password',
            ]
        );

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
