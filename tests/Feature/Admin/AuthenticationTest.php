<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

final class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Str0ng!Passw0rd#2026';

    public function test_login_page_renders(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Log in')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_admin_can_log_in_and_session_is_regenerated(): void
    {
        $admin = Admin::factory()->create();
        $sessionIdBefore = session()->getId();

        $this->post(route('admin.login.store'), ['email' => $admin->email, 'password' => self::PASSWORD])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->assertNotSame($sessionIdBefore, session()->getId());
        $this->assertNotNull($admin->fresh()->last_login_at);
    }

    public function test_wrong_password_is_rejected_with_a_generic_message(): void
    {
        $admin = Admin::factory()->create();

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), ['email' => $admin->email, 'password' => 'wrong-password'])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);

        $this->assertGuest();
    }

    public function test_login_is_locked_after_five_failed_attempts(): void
    {
        $admin = Admin::factory()->create();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('admin.login.store'), ['email' => $admin->email, 'password' => 'wrong']);
        }

        $this->post(route('admin.login.store'), ['email' => $admin->email, 'password' => self::PASSWORD])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_guests_are_redirected_away_from_admin_pages(): void
    {
        foreach (['admin.dashboard', 'admin.settings.edit', 'admin.account.edit'] as $routeName) {
            $this->get(route($routeName))->assertRedirect(route('admin.login'));
        }
    }

    public function test_logged_in_admin_is_redirected_away_from_login_page(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_logout_invalidates_the_session(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_logout_requires_post(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get('/admin/logout')
            ->assertStatus(405);
    }

    public function test_there_is_no_public_registration(): void
    {
        $this->get('/register')->assertNotFound();
        $this->get('/admin/register')->assertNotFound();
    }

    public function test_admin_pages_are_not_cacheable(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_reset_link_response_does_not_reveal_whether_email_exists(): void
    {
        Notification::fake();
        $admin = Admin::factory()->create();

        $knownResponse = $this->post(route('admin.password.email'), ['email' => $admin->email]);
        $unknownResponse = $this->post(route('admin.password.email'), ['email' => 'nobody@example.com']);

        $this->assertSame(session('status'), $unknownResponse->getSession()->get('status'));
        $knownResponse->assertSessionHas('status');
        Notification::assertSentTo($admin, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_a_valid_token(): void
    {
        $admin = Admin::factory()->create();
        $token = Password::broker('admins')->createToken($admin);
        $newPassword = 'An0ther!Secure#Pass';

        $this->post(route('admin.password.store'), [
            'token' => $token,
            'email' => $admin->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertRedirect(route('admin.login'));

        $this->post(route('admin.login.store'), ['email' => $admin->email, 'password' => $newPassword]);
        $this->assertAuthenticatedAs($admin);
    }

    public function test_password_reset_rejects_an_invalid_token(): void
    {
        $admin = Admin::factory()->create();

        $this->post(route('admin.password.store'), [
            'token' => 'forged-token',
            'email' => $admin->email,
            'password' => 'An0ther!Secure#Pass',
            'password_confirmation' => 'An0ther!Secure#Pass',
        ])->assertSessionHasErrors('email');
    }

    public function test_password_reset_enforces_the_password_policy(): void
    {
        $admin = Admin::factory()->create();
        $token = Password::broker('admins')->createToken($admin);

        $this->post(route('admin.password.store'), [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');
    }
}
