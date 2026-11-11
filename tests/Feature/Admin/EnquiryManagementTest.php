<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryStatus;
use App\Models\Admin;
use App\Models\Enquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EnquiryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create());
    }

    public function test_enquiries_and_contact_messages_are_listed_separately(): void
    {
        Enquiry::factory()->create(['name' => 'Product Person']);
        Enquiry::factory()->contact()->create(['name' => 'Contact Person']);

        $this->get(route('admin.enquiries.index'))->assertOk()->assertSee('Product Person')->assertDontSee('Contact Person');
        $this->get(route('admin.contact-messages.index'))->assertOk()->assertSee('Contact Person')->assertDontSee('Product Person');
    }

    public function test_opening_an_enquiry_marks_it_as_read(): void
    {
        $enquiry = Enquiry::factory()->create();

        $this->get(route('admin.enquiries.show', $enquiry))->assertOk()->assertSee($enquiry->name);

        $this->assertNotNull($enquiry->fresh()->read_at);
    }

    public function test_visitor_input_is_escaped_in_the_admin(): void
    {
        $enquiry = Enquiry::factory()->create(['name' => '<script>alert(1)</script>', 'message' => '<img src=x onerror=alert(1)>']);

        $this->get(route('admin.enquiries.show', $enquiry))
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false);

        $this->get(route('admin.enquiries.index'))->assertDontSee('<script>alert(1)</script>', false);
        $this->get(route('admin.dashboard'))->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_status_and_notes_can_be_updated(): void
    {
        $enquiry = Enquiry::factory()->create();

        $this->put(route('admin.enquiries.update', $enquiry), ['status' => 'in_progress', 'admin_notes' => 'Called back'])
            ->assertRedirect(route('admin.enquiries.show', $enquiry));

        $this->assertSame(EnquiryStatus::InProgress, $enquiry->fresh()->status);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $enquiry = Enquiry::factory()->create();

        $this->put(route('admin.enquiries.update', $enquiry), ['status' => 'hacked'])->assertSessionHasErrors('status');
    }

    public function test_enquiry_can_be_deleted(): void
    {
        $enquiry = Enquiry::factory()->contact()->create();

        $this->delete(route('admin.enquiries.destroy', $enquiry))->assertRedirect(route('admin.contact-messages.index'));

        $this->assertModelMissing($enquiry);
    }

    public function test_sidebar_shows_unread_counts(): void
    {
        Enquiry::factory()->count(3)->create();

        $this->get(route('admin.dashboard'))->assertOk()->assertSee('3<span class="visually-hidden"> unread</span>', false);
    }
}
