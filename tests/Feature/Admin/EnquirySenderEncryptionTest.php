<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Enquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

final class EnquirySenderEncryptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_sender_ip_and_browser_are_stored_encrypted(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])
            ->withHeader('User-Agent', 'Example Browser 2.0')
            ->post(route('contact.store'), ['name' => 'Asha', 'phone' => '+91 98765 00000', 'message' => 'Hello']);

        $raw = DB::table('enquiries')->sole();

        $this->assertStringNotContainsString('203.0.113.9', (string) $raw->ip_address);
        $this->assertStringNotContainsString('Example Browser', (string) $raw->user_agent);
        $this->assertSame('203.0.113.9', Crypt::decryptString($raw->ip_address));

        $enquiry = Enquiry::query()->sole();
        $this->assertSame('Example Browser 2.0', $enquiry->user_agent);
        $this->assertArrayNotHasKey('ip_address', $enquiry->toArray());
    }

    public function test_admin_sees_hidden_details_and_can_reveal_them_with_a_log_entry(): void
    {
        $admin = Admin::factory()->create();
        $enquiry = Enquiry::factory()->create(['ip_address' => '198.51.100.4', 'user_agent' => 'Example Browser 3.0']);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertOk()
            ->assertSee('Hidden (encrypted)')
            ->assertDontSee('198.51.100.4');

        Log::shouldReceive('channel')->with('security')->once()->andReturnSelf();
        Log::shouldReceive('notice')->once()->withArgs(fn (string $message, array $context): bool => $message === 'Enquiry sender details revealed' && $context['enquiry_id'] === $enquiry->id);

        $this->actingAs($admin)
            ->post(route('admin.enquiries.reveal-sender', $enquiry))
            ->assertRedirect(route('admin.enquiries.show', $enquiry));

        $this->actingAs($admin)
            ->withSession(['revealed_sender' => ['ip_address' => '198.51.100.4', 'user_agent' => 'Example Browser 3.0']])
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertSee('198.51.100.4')
            ->assertSee('Example Browser 3.0');
    }

    public function test_guests_cannot_reveal_and_missing_details_say_not_recorded(): void
    {
        $enquiry = Enquiry::factory()->create(['ip_address' => null, 'user_agent' => null]);

        $this->post(route('admin.enquiries.reveal-sender', $enquiry))->assertRedirect(route('admin.login'));

        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertSee('Not recorded for this')
            ->assertDontSee(route('admin.enquiries.reveal-sender', $enquiry), false);
    }

    public function test_details_saved_with_another_key_show_a_friendly_message(): void
    {
        $enquiry = Enquiry::factory()->create();
        DB::table('enquiries')->where('id', $enquiry->id)->update(['ip_address' => 'eyJub3QiOiJ2YWxpZCJ9']);

        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.enquiries.reveal-sender', $enquiry))
            ->assertRedirect(route('admin.enquiries.show', $enquiry))
            ->assertSessionHas('error');
    }
}
