<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\SiteSettingKey;
use App\Models\Admin;
use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_settings_page_renders(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Google Analytics 4 Measurement ID');
    }

    public function test_admin_can_save_settings(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.settings.update'), $this->validPayload([
                SiteSettingKey::Ga4MeasurementId->value => ' g-abc123xyz ',
                SiteSettingKey::WhatsappNumber->value => '+91 98765-43210',
            ]))
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHasNoErrors();

        $settings = app(SiteSettingService::class);
        $this->assertSame('G-ABC123XYZ', $settings->get(SiteSettingKey::Ga4MeasurementId));
        $this->assertSame('919876543210', $settings->get(SiteSettingKey::WhatsappNumber));
    }

    public function test_guest_cannot_save_settings(): void
    {
        $this->put(route('admin.settings.update'), $this->validPayload())
            ->assertRedirect(route('admin.login'));

        $this->assertDatabaseCount('site_settings', 0);
    }

    public function test_tracking_ids_containing_script_are_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.settings.update'), $this->validPayload([
                SiteSettingKey::Ga4MeasurementId->value => "G-1');alert(1);//",
                SiteSettingKey::FacebookPixelId->value => '<script>alert(1)</script>',
            ]))
            ->assertSessionHasErrors([
                SiteSettingKey::Ga4MeasurementId->value,
                SiteSettingKey::FacebookPixelId->value,
            ]);

        $this->assertDatabaseCount('site_settings', 0);
    }

    public function test_unknown_setting_keys_are_never_stored(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.settings.update'), $this->validPayload(['malicious_key' => 'value']));

        $this->assertDatabaseMissing('site_settings', ['key' => 'malicious_key']);
    }

    public function test_javascript_urls_are_rejected_for_social_links(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.settings.update'), $this->validPayload([
                SiteSettingKey::FacebookUrl->value => 'javascript:alert(1)',
            ]))
            ->assertSessionHasErrors(SiteSettingKey::FacebookUrl->value);
    }

    public function test_logo_is_stored_under_a_random_name(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.settings.update'), $this->validPayload([
                'logo' => UploadedFile::fake()->image('../../evil name.png', 400, 120),
            ]))
            ->assertSessionHasNoErrors();

        $path = SiteSetting::query()->where('key', SiteSettingKey::LogoPath->value)->value('value');

        $this->assertMatchesRegularExpression('#^settings/[A-Za-z0-9]{40}\.png$#', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_replacing_the_logo_deletes_the_old_file(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)->put(route('admin.settings.update'), $this->validPayload([
            'logo' => UploadedFile::fake()->image('first.png'),
        ]));
        $firstPath = app(SiteSettingService::class)->get(SiteSettingKey::LogoPath);

        $this->actingAs($admin)->put(route('admin.settings.update'), $this->validPayload([
            'logo' => UploadedFile::fake()->image('second.png'),
        ]));

        Storage::disk('public')->assertMissing($firstPath);
    }

    public function test_svg_and_non_image_logos_are_rejected(): void
    {
        $admin = Admin::factory()->create();

        foreach ([
            UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            UploadedFile::fake()->createWithContent('logo.png', '<?php echo "pwned"; ?>'),
            UploadedFile::fake()->create('logo.php', 10, 'application/x-php'),
        ] as $file) {
            $this->actingAs($admin)
                ->put(route('admin.settings.update'), $this->validPayload(['logo' => $file]))
                ->assertSessionHasErrors('logo');
        }

        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_oversized_logo_is_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.settings.update'), $this->validPayload([
                'logo' => UploadedFile::fake()->image('big.png')->size(5000),
            ]))
            ->assertSessionHasErrors('logo');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return [
            SiteSettingKey::SiteName->value => 'Acme Pumps',
            SiteSettingKey::ContactEmail->value => 'sales@example.com',
            ...$overrides,
        ];
    }
}
