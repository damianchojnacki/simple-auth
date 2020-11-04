<?php

namespace Tests\Feature;

use App\Mail\ConfirmEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Mail::assertNothingSent();
    }

    public function testUserCanRegister()
    {
        $email = $this->faker->email;

        $response = $this->post('/api/register', [
            'email' => $email,
            'password' => $this->faker->password,
        ]);

        $response->assertStatus(200);

        Mail::assertSent(ConfirmEmail::class);

        $this->assertNotNull(User::where('email', $email)->first());
    }

    public function testUserCantRegisterTwiceAtTheSameEmail()
    {
        $email = $this->faker->email;

        $this->post('/api/register', [
            'email' => $email,
            'password' => $this->faker->password,
        ]);

        $response = $this->post('/api/register', [
            'email' => $email,
            'password' => $this->faker->password,
        ]);

        $response->assertStatus(422);
    }

    public function testUserCanConfirmEmail()
    {
        $email = $this->faker->email;

        $this->post('/api/register', [
            'email' => $email,
            'password' => $this->faker->password,
        ]);

        $user = User::where('email', $email)->first();

        //email not confirmed yet
        $this->assertNull($user->email_verified_at);

        $this->get($user->getEmailConfirmationUrl());

        $user = User::where('email', $email)->first();

        //email should be confirmed now
        $this->assertNotNull($user->email_verified_at);
    }

    public function testUserCanDeleteAccount()
    {
        $email = $this->faker->email;

        $user = User::create([
            'email' => $email,
            'password' => $this->faker->password,
            'hash' => Str::random(32),
        ]);

        $user->markEmailAsVerified();

        $response = $this->delete('/api/user', [
            'id' => $user->id,
            'hash' => $user->hash,
        ]);

        $response->assertStatus(200);

        $this->assertNull(User::where('email', $email)->first());
    }

    public function testGuestCantDeleteUserAccount()
    {
        $email = $this->faker->email;

        $user = User::create([
            'email' => $email,
            'password' => $this->faker->password,
            'hash' => Str::random(32),
        ]);

        $response = $this->delete('/api/user', [
            'id' => $user->id,
        ]);

        $response->assertStatus(422);

        $this->assertNotNull(User::where('email', $email)->first());
    }
}
