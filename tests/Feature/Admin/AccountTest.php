<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class AccountTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Str0ng!Passw0rd#2026';

    public function test_admin_can_update_profile(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.account.profile.update'), ['name' => 'New Name', 'email' => 'new@example.com'])
            ->assertRedirect(route('admin.account.edit'))
            ->assertSessionHasNoErrors();

        $this->assertSame('new@example.com', $admin->fresh()->email);
    }

    public function test_profile_update_ignores_unexpected_fields(): void
    {
        $admin = Admin::factory()->create();
        $originalHash = $admin->password;

        $this->actingAs($admin)->put(route('admin.account.profile.update'), [
            'name' => 'New Name',
            'email' => $admin->email,
            'password' => 'Inj3cted!Password#1',
            'id' => 999,
        ]);

        $admin->refresh();
        $this->assertSame($originalHash, $admin->password);
        $this->assertNotSame(999, $admin->id);
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.account.password.update'), [
                'current_password' => 'not-my-password',
                'password' => 'N3w!Secure#Password',
                'password_confirmation' => 'N3w!Secure#Password',
            ])
            ->assertSessionHasErrorsIn('updatePassword', 'current_password');

        $this->assertTrue(Hash::check(self::PASSWORD, $admin->fresh()->password));
    }

    public function test_admin_can_change_password(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.account.password.update'), [
                'current_password' => self::PASSWORD,
                'password' => 'N3w!Secure#Password',
                'password_confirmation' => 'N3w!Secure#Password',
            ])
            ->assertRedirect(route('admin.account.edit'))
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('N3w!Secure#Password', $admin->fresh()->password));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_weak_new_password_is_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.account.password.update'), [
                'current_password' => self::PASSWORD,
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertSessionHasErrorsIn('updatePassword', 'password');
    }
}
