<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminQuickUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_change_user_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->post(route('admin.users.quick-update'), [
                'user_id' => $user->id,
                'role' => 'retailer',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'retailer']);
    }

    public function test_admin_can_reset_user_password_via_quick_update()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['password' => bcrypt('oldpass123')]);

        $this->actingAs($admin)
            ->post(route('admin.users.quick-update'), [
                'user_id' => $user->id,
                'password' => 'newsecurepw',
                'password_confirmation' => 'newsecurepw',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('newsecurepw', $user->fresh()->password));
    }

    public function test_admin_can_set_dashboard_modules_via_quick_update()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.users.quick-update'), [
                'user_id' => $user->id,
                'dashboard_modules' => ['profile', 'notifications'],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertEquals(['profile','notifications'], $user->fresh()->dashboard_modules);
    }
}
