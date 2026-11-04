<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\EnquiryType;
use App\Enums\SiteSettingKey;
use App\Mail\EnquiryReceivedMail;
use App\Models\Enquiry;
use App\Models\Product;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class EnquirySubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        app(SiteSettingService::class)->update([SiteSettingKey::EnquiryNotificationEmail->value => 'owner@example.com']);
    }

    public function test_product_enquiry_is_saved_and_emailed(): void
    {
        $product = Product::factory()->create();

        $this->from('/products/'.$product->slug)
            ->post(route('enquiries.store'), $this->productPayload($product))
            ->assertRedirect('/products/'.$product->slug)
            ->assertSessionHas('enquiry_status');

        $enquiry = Enquiry::query()->sole();

        $this->assertSame(EnquiryType::Product, $enquiry->type);
        $this->assertSame($product->id, $enquiry->product_id);
        $this->assertSame($product->name, $enquiry->product_name);
        $this->assertNotNull($enquiry->ip_address);
        Mail::assertSent(EnquiryReceivedMail::class, static fn (EnquiryReceivedMail $mail): bool => $mail->hasTo('owner@example.com'));
    }

    public function test_json_requests_get_a_json_response(): void
    {
        $product = Product::factory()->create();

        $this->postJson(route('enquiries.store'), $this->productPayload($product))->assertCreated()->assertJsonStructure(['message']);
    }

    public function test_hidden_products_cannot_be_enquired_about(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->post(route('enquiries.store'), $this->productPayload($product))->assertSessionHasErrors('product');
        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_status_and_notes_cannot_be_injected(): void
    {
        $product = Product::factory()->create();

        $this->post(route('enquiries.store'), $this->productPayload($product, [
            'status' => 'closed',
            'admin_notes' => 'injected',
            'read_at' => now()->toDateTimeString(),
            'type' => 'contact',
        ]));

        $enquiry = Enquiry::query()->sole();
        $this->assertSame('new', $enquiry->status->value);
        $this->assertSame(EnquiryType::Product, $enquiry->type);
        $this->assertNull($enquiry->admin_notes);
        $this->assertNull($enquiry->read_at);
    }

    public function test_honeypot_submissions_are_silently_dropped(): void
    {
        $product = Product::factory()->create();

        $this->post(route('enquiries.store'), $this->productPayload($product, ['website' => 'http://spam.example']))
            ->assertSessionHas('enquiry_status');

        $this->assertDatabaseCount('enquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_required_fields_and_phone_format_are_validated(): void
    {
        $product = Product::factory()->create();

        $this->post(route('enquiries.store'), ['product' => $product->slug, 'phone' => 'call me <b>now</b>'])
            ->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_enquiries_are_rate_limited(): void
    {
        $product = Product::factory()->create();

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->post(route('enquiries.store'), $this->productPayload($product))->assertRedirect();
        }

        $this->post(route('enquiries.store'), $this->productPayload($product))->assertStatus(429);
        $this->assertDatabaseCount('enquiries', 3);
    }

    public function test_contact_message_is_saved(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Asha',
            'phone' => '+91 98765 43210',
            'email' => 'asha@example.com',
            'subject' => 'Dealer enquiry',
            'message' => 'Do you have a dealer in Pune?',
        ])->assertSessionHas('contact_status');

        $this->assertSame(EnquiryType::Contact, Enquiry::query()->sole()->type);
    }

    public function test_enquiry_is_saved_even_if_email_fails(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));
        $product = Product::factory()->create();

        $this->post(route('enquiries.store'), $this->productPayload($product))->assertSessionHas('enquiry_status');

        $this->assertDatabaseCount('enquiries', 1);
    }

    public function test_notification_email_escapes_visitor_input(): void
    {
        $enquiry = Enquiry::factory()->create(['message' => '<script>alert(1)</script> [click](http://evil.example)']);

        $html = (new EnquiryReceivedMail($enquiry))->render();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringNotContainsString('href="http://evil.example"', $html);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function productPayload(Product $product, array $overrides = []): array
    {
        return [
            'product' => $product->slug,
            'name' => 'Ravi Kumar',
            'phone' => '+91 98765 43210',
            'email' => 'ravi@example.com',
            'company' => 'Kumar Industries',
            'city' => 'Ahmedabad',
            'message' => 'Please share a quotation for 20 units.',
            ...$overrides,
        ];
    }
}
