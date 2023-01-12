<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_user_can_login()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->post(route('auth.login'), $credentials);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'response' => [
                'user' => [
                    'id',
                    'name',
                    'email'
                ],
                'token_type',
                'token'
            ]
        ]);
    }

    public function test_if_user_can_logout()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $data = $this->post(route('auth.login'), $credentials)
            ->assertSuccessful()
            ->json();

        $this->post(route('auth.logout'), [], [
            'Authorization' => "{$data['response']['token_type']} {$data['response']['token']}"
        ])->assertNoContent();
    }
}
