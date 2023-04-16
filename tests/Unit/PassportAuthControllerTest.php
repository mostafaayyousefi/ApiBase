<?php

namespace Tests\Unit\Http\Controllers\Api;

use PHPUnit\Framework\TestCase;

class PassportAuthControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function login_displays_the_login_form()
{
    $response = $this->get(route('login'));
    $response->assertStatus(200);
    $response->assertViewIs('auth.login');
}

public function login_displays_validation_errors()
{
    $response = $this->post('/login', []);
    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
}




}
