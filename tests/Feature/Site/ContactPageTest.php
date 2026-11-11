<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\ContactPageSection;
use App\Models\Admin;
use App\Models\Enquiry;
use App\Services\PageSectionService;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    private const MAP_URL = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3671!2d72.57!3d23.02';

    public function test_contact_page_shows_cards_form_and_map(): void
    {
        app(SiteSettingService::class)->update([
            'contact_address' => '12 Industrial Estate, Pune',
            'contact_phone' => '+91 98765 43210',
            'contact_email' => 'sales@example.com',
        ]);
        app(PageSectionService::class)->save(ContactPageSection::Cards, ['address_title' => 'Visit Us', 'phone_title' => 'Call Us', 'email_title' => 'Email Us', 'extra_phone' => '+91 22 1234 5678', 'extra_email' => null], [], []);
        app(PageSectionService::class)->save(ContactPageSection::Map, ['embed_url' => self::MAP_URL], [], []);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('<h1 class="page-banner__title" id="page-title">Contact Us</h1>', false)
            ->assertSee('12 Industrial Estate, Pune')
            ->assertSee('href="tel:+919876543210"', false)
            ->assertSee('href="tel:+912212345678"', false)
            ->assertSee('href="mailto:sales@example.com"', false)
            ->assertSee('action="'.route('contact.store').'"', false)
            ->assertSee('src="'.e(self::MAP_URL).'"', false)
            ->assertDontSee('noindex', false);
    }

    public function test_map_and_empty_cards_are_hidden_when_not_configured(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertDontSee('contact-map__frame', false)
            ->assertDontSee('contact-cards__grid', false);
    }

    public function test_contact_form_returns_to_the_form_with_errors_or_a_thank_you(): void
    {
        $this->from(route('contact'))
            ->post(route('contact.store'), ['name' => '', 'phone' => 'abc', 'message' => ''])
            ->assertRedirect(route('contact').'#contact-form')
            ->assertSessionHasErrors(['name', 'phone', 'message']);

        $this->from(route('contact'))
            ->post(route('contact.store'), ['name' => 'Asha', 'phone' => '+91 98765 00000', 'message' => 'Need a gauging pig'])
            ->assertRedirect(route('contact').'#contact-form')
            ->assertSessionHas('contact_status');

        $this->assertDatabaseHas('enquiries', ['name' => 'Asha', 'type' => 'contact']);
        $this->followingRedirects()->from(route('contact'))
            ->post(route('contact.store'), ['name' => 'Ravi', 'phone' => '+91 98765 00001', 'message' => 'Hello'])
            ->assertSee('Thank you! Your message has been sent.');
    }

    public function test_admin_can_paste_the_google_maps_iframe_code(): void
    {
        $iframe = '<iframe src="'.self::MAP_URL.'" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.contact-page.update'), $this->payload(['map' => ['embed_url' => $iframe]]))
            ->assertRedirect(route('admin.contact-page.edit'))
            ->assertSessionHasNoErrors();

        $this->assertSame(self::MAP_URL, app(PageSectionService::class)->section(ContactPageSection::Map)['embed_url']);
    }

    public function test_non_google_map_links_are_rejected(): void
    {
        $admin = Admin::factory()->create();

        foreach (['https://evil.example/maps/embed?x=1', '<iframe src="javascript:alert(1)"></iframe>', 'https://www.google.com/search?q=x'] as $bad) {
            $this->actingAs($admin)
                ->put(route('admin.contact-page.update'), $this->payload(['map' => ['embed_url' => $bad]]))
                ->assertSessionHasErrors('map.embed_url');
        }

        $this->assertDatabaseMissing('page_sections', ['page' => 'contact']);
    }

    public function test_contact_page_editor_requires_login(): void
    {
        $this->get(route('admin.contact-page.edit'))->assertRedirect(route('admin.login'));
        $this->put(route('admin.contact-page.update'), $this->payload())->assertRedirect(route('admin.login'));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'banner' => ['eyebrow' => 'Help', 'title' => 'Contact Us'],
            'cards' => ['address_title' => 'Visit Us', 'phone_title' => 'Call Us', 'email_title' => 'Email Us'],
            'form' => ['heading' => 'Get In Touch', 'form_heading' => 'Fill Up The Form', 'button_label' => 'Send'],
            'map' => ['embed_url' => ''],
        ], $overrides);
    }
}
