<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\AboutSection;
use App\Services\PageSectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_shows_the_default_banner(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<h1 class="page-banner__title" id="page-title">About Us</h1>', false)
            ->assertSee('Pipeline Pigging Experts')
            ->assertSee('page-banner--no-image', false)
            ->assertSee('<title>About Us', false);
    }

    public function test_banner_text_is_escaped_and_empty_eyebrow_is_hidden(): void
    {
        app(PageSectionService::class)->save(AboutSection::Banner, ['title' => '<script>alert(1)</script>', 'eyebrow' => null], [], []);

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('page-banner__eyebrow', false);
    }
}
