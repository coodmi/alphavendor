<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_password_reset_sends_notification()
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create(['email' => 'reset@example.com', 'password' => bcrypt('oldpassword')]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertTrue(auth()->check());
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(password_verify('newpassword123', $user->fresh()->password));
    }
}
