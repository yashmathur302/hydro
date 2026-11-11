<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Session\PrivateDatabaseSessionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class SessionPrivacyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['session.driver' => 'database']);
        $this->app['session']->forgetDrivers();
    }

    public function test_database_sessions_do_not_store_ip_address_or_browser(): void
    {
        $this->assertInstanceOf(PrivateDatabaseSessionHandler::class, $this->app['session']->driver()->getHandler());

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])
            ->withHeader('User-Agent', 'Example Browser 1.0')
            ->get(route('contact'))
            ->assertOk();

        $session = DB::table('sessions')->sole();

        $this->assertNull($session->ip_address);
        $this->assertNull($session->user_agent);
    }

    public function test_admin_sessions_still_record_the_admin_for_logout_everywhere(): void
    {
        $admin = Admin::factory()->create(['password' => 'Str0ng!Passw0rd#2026']);

        $this->post(route('admin.login.store'), ['email' => $admin->email, 'password' => 'Str0ng!Passw0rd#2026'])
            ->assertRedirect(route('admin.dashboard'));

        $session = DB::table('sessions')->where('user_id', $admin->id)->sole();
        $this->assertNull($session->ip_address);
    }
}
