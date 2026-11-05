<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Industry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class IndustryAndClientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guests_cannot_manage_industries_or_clients(): void
    {
        $this->get(route('admin.industries.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.clients.store'), ['name' => 'X'])->assertRedirect(route('admin.login'));
    }

    public function test_industry_can_be_created_with_icon_and_photo(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.industries.store'), [
                'name' => 'Oil & Gas',
                'description' => 'Pigging for oil and gas lines.',
                'icon' => 'oil-gas',
                'is_active' => '1',
                'image' => UploadedFile::fake()->image('strip.jpg', 1200, 3800),
            ])
            ->assertRedirect(route('admin.industries.index'));

        $industry = Industry::query()->sole();
        $this->assertSame('oil-gas', $industry->icon->value);
        $this->assertSame(600, getimagesizefromstring(Storage::disk('public')->get($industry->image_path))[0]);
    }

    public function test_unknown_icon_is_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.industries.store'), ['name' => 'X', 'icon' => '"><script>'])
            ->assertSessionHasErrors('icon');
    }

    public function test_deleting_an_industry_removes_its_photo(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin)->post(route('admin.industries.store'), [
            'name' => 'Water', 'icon' => 'water', 'image' => UploadedFile::fake()->image('a.jpg'),
        ]);
        $industry = Industry::query()->sole();

        $this->actingAs($admin)->delete(route('admin.industries.destroy', $industry))->assertRedirect(route('admin.industries.index'));

        Storage::disk('public')->assertMissing($industry->image_path);
    }

    public function test_client_requires_a_real_logo_when_created(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)->post(route('admin.clients.store'), ['name' => 'Acme'])->assertSessionHasErrors('logo');
        $this->actingAs($admin)->post(route('admin.clients.store'), [
            'name' => 'Acme',
            'logo' => UploadedFile::fake()->createWithContent('logo.png', '<svg onload="alert(1)"></svg>'),
        ])->assertSessionHasErrors('logo');

        $this->assertDatabaseCount('clients', 0);
    }

    public function test_client_can_be_created_and_logo_replaced(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)->post(route('admin.clients.store'), [
            'name' => 'Acme', 'website_url' => 'https://acme.example', 'is_active' => '1',
            'logo' => UploadedFile::fake()->image('logo.png', 300, 90),
        ])->assertRedirect(route('admin.clients.index'));

        $client = Client::query()->sole();
        $firstLogo = $client->logo_path;

        $this->actingAs($admin)->put(route('admin.clients.update', $client), [
            'name' => 'Acme', 'logo' => UploadedFile::fake()->image('new.png', 300, 90),
        ])->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($firstLogo);
        $this->assertNotSame($firstLogo, $client->fresh()->logo_path);
    }

    public function test_javascript_website_links_are_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.clients.store'), [
                'name' => 'Acme', 'website_url' => 'javascript:alert(1)',
                'logo' => UploadedFile::fake()->image('logo.png'),
            ])
            ->assertSessionHasErrors('website_url');
    }
}
