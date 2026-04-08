<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserDashboardVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dashboard_respects_dashboard_modules()
    {
        $user = User::factory()->create(['dashboard_modules' => ['profile'], 'role' => 'user']);

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertStatus(200)
            ->assertSee('Edit Profile')
            ->assertDontSee('View Orders')
            ->assertDontSee('My Wishlist');
    }

    public function test_default_dashboard_shows_all_when_no_modules_set()
    {
        $user = User::factory()->create(['dashboard_modules' => null, 'role' => 'user']);

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertStatus(200)
            ->assertSee('View Orders')
            ->assertSee('My Wishlist')
            ->assertSee('Edit Profile');
    }
}
