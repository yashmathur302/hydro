<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Page;
use App\Support\Content\MarkdownRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create());
    }

    public function test_legal_pages_exist_as_drafts(): void
    {
        $this->assertDatabaseHas('pages', ['slug' => 'privacy-policy', 'is_active' => false]);
        $this->assertDatabaseHas('pages', ['slug' => 'terms-and-conditions', 'is_active' => false]);

        $this->get(route('admin.pages.index'))->assertOk()->assertSee('Privacy Policy')->assertSee('Terms &amp; Conditions', false);
    }

    public function test_page_can_be_edited_and_published(): void
    {
        $page = Page::query()->where('slug', 'privacy-policy')->sole();

        $this->get(route('admin.pages.edit', $page))->assertOk();

        $this->put(route('admin.pages.update', $page), [
            'title' => 'Privacy Policy',
            'content' => "## Data we collect\n\nOnly what you send us.",
            'is_active' => '1',
        ])->assertRedirect(route('admin.pages.edit', $page));

        $this->assertTrue($page->fresh()->is_active);
    }

    public function test_page_can_be_created_and_deleted(): void
    {
        $this->post(route('admin.pages.store'), ['title' => 'Shipping Policy', 'is_active' => '0']);

        $page = Page::query()->where('slug', 'shipping-policy')->sole();

        $this->delete(route('admin.pages.destroy', $page))->assertRedirect(route('admin.pages.index'));
        $this->assertModelMissing($page);
    }

    public function test_markdown_rendering_escapes_html_and_unsafe_links(): void
    {
        $html = (string) app(MarkdownRenderer::class)->render("## Title\n\n**Bold** <script>alert(1)</script>\n\n[x](javascript:alert(1))");

        $this->assertStringContainsString('<h2>Title</h2>', $html);
        $this->assertStringContainsString('<strong>Bold</strong>', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('javascript:', $html);
    }
}
