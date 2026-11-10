<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class CertificatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_only_the_first_three_active_certificates(): void
    {
        foreach (['Fourth' => 4, 'First' => 1, 'Third' => 3, 'Second' => 2] as $name => $order) {
            Certificate::factory()->create(['name' => $name.' Certificate', 'sort_order' => $order]);
        }
        Certificate::factory()->create(['name' => 'Hidden Certificate', 'sort_order' => 0, 'is_active' => false]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['First Certificate', 'Second Certificate', 'Third Certificate'])
            ->assertDontSee('Fourth Certificate')
            ->assertDontSee('Hidden Certificate')
            ->assertSee('href="'.route('certificates').'"', false);
    }

    public function test_home_section_is_hidden_without_certificates(): void
    {
        $this->get(route('home'))->assertOk()->assertDontSee('certificates-heading', false);
    }

    public function test_certificates_page_lists_every_active_certificate_opening_in_a_new_tab(): void
    {
        $certificates = Certificate::factory()->count(5)->create();
        Certificate::factory()->create(['name' => 'Hidden Certificate', 'is_active' => false]);

        $response = $this->get(route('certificates'))
            ->assertOk()
            ->assertSee('<h1 class="page-banner__title" id="page-title">Certificates</h1>', false)
            ->assertDontSee('Hidden Certificate')
            ->assertDontSee('noindex', false);

        foreach ($certificates as $certificate) {
            $response->assertSee($certificate->name)
                ->assertSee('href="'.Storage::disk('public')->url($certificate->file_path).'" target="_blank" rel="noopener"', false);
        }
    }

    public function test_certificate_text_is_escaped(): void
    {
        Certificate::factory()->create(['name' => '<script>alert(1)</script>', 'issuer' => '<b>x</b>']);

        $this->get(route('certificates'))
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<b>x</b>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }
}
