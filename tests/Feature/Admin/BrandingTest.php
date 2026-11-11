<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\SiteSettingKey;
use App\Models\Admin;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class BrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_name_is_shown_until_a_logo_is_uploaded(): void
    {
        $this->get(route('admin.login'))
            ->assertSee('<h4 class="logo-title">Lucky Instru Hydro</h4>', false)
            ->assertDontSee('class="brand-logo"', false);
    }

    public function test_uploaded_logo_is_shown_on_login_and_inside_the_admin(): void
    {
        Storage::fake('public');
        $settings = app(SiteSettingService::class);
        $settings->replaceFile(SiteSettingKey::LogoPath, UploadedFile::fake()->image('logo.png', 400, 100));
        $logoUrl = $settings->fileUrl(SiteSettingKey::LogoPath);

        $this->get(route('admin.login'))
            ->assertSee('<img class="brand-logo" src="'.$logoUrl.'" alt="Lucky Instru Hydro">', false);

        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertSee('<img class="brand-logo" src="'.$logoUrl.'" alt="Lucky Instru Hydro">', false);
    }
}
