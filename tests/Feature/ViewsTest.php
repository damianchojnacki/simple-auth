<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    public function testUserCanViewRegistrationPage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testGuestCantViewConfirmEmailPage()
    {
        $response = $this->get('/email/confirmed');

        $response->assertRedirect();
    }
}
