<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_if_user_can_register()
    {
        $data = [
            'name' => $this->faker()->firstName() . ' ' . $this->faker()->lastName(),
            'email' => $this->faker()->unique()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post(route('auth.register'), $data);

        $response->assertCreated();
    }
}
