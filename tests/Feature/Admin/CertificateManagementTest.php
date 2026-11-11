<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\CertificatesPageSection;
use App\Models\Admin;
use App\Models\Certificate;
use App\Services\PageSectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class CertificateManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guests_cannot_manage_certificates(): void
    {
        $certificate = Certificate::factory()->create();

        $this->get(route('admin.certificates.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.certificates.store'), ['name' => 'ISO'])->assertRedirect(route('admin.login'));
        $this->delete(route('admin.certificates.destroy', $certificate))->assertRedirect(route('admin.login'));
        $this->get(route('admin.certificates-page.edit'))->assertRedirect(route('admin.login'));

        $this->assertModelExists($certificate);
    }

    public function test_admin_can_add_a_certificate_with_pdf_and_cover(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.certificates.store'), [
                'name' => 'ISO 9001:2015',
                'issuer' => 'Bureau of Standards',
                'file' => $this->pdf(),
                'cover_image' => UploadedFile::fake()->image('cover.jpg', 1200, 1600),
                'is_active' => '1',
                'sort_order' => '2',
            ])
            ->assertRedirect(route('admin.certificates.index'))
            ->assertSessionHasNoErrors();

        $certificate = Certificate::query()->sole();

        $this->assertSame('ISO 9001:2015', $certificate->name);
        $this->assertSame(2, $certificate->sort_order);
        $this->assertStringStartsWith('certificates/', $certificate->file_path);
        $this->assertStringEndsWith('.pdf', $certificate->file_path);
        $this->assertStringNotContainsString('iso', strtolower(basename($certificate->file_path)));
        Storage::disk('public')->assertExists($certificate->file_path);
        $this->assertStringEndsWith('.webp', (string) $certificate->cover_image_path);
        $this->assertSame(800, getimagesizefromstring(Storage::disk('public')->get($certificate->cover_image_path))[0]);
    }

    public function test_pdf_is_required_and_must_be_a_real_pdf(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.certificates.store'), ['name' => 'No file'])
            ->assertSessionHasErrors('file');

        $this->actingAs($admin)
            ->post(route('admin.certificates.store'), [
                'name' => 'Fake',
                'file' => UploadedFile::fake()->createWithContent('cert.pdf', '<script>alert(1)</script>'),
            ])
            ->assertSessionHasErrors('file');

        $this->actingAs($admin)
            ->post(route('admin.certificates.store'), [
                'name' => 'Wrong type',
                'file' => UploadedFile::fake()->image('cert.png'),
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('certificates', 0);
    }

    public function test_editing_keeps_the_pdf_unless_replaced_and_deletes_replaced_files(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin)->post(route('admin.certificates.store'), [
            'name' => 'Original',
            'file' => $this->pdf(),
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 600, 800),
            'is_active' => '1',
        ]);
        $certificate = Certificate::query()->sole();
        $firstPdf = $certificate->file_path;
        $firstCover = $certificate->cover_image_path;

        $this->actingAs($admin)
            ->put(route('admin.certificates.update', $certificate), ['name' => 'Renamed', 'is_active' => '1'])
            ->assertSessionHasNoErrors();
        $this->assertSame($firstPdf, $certificate->fresh()->file_path);

        $this->actingAs($admin)
            ->put(route('admin.certificates.update', $certificate), [
                'name' => 'Renamed',
                'file' => $this->pdf(),
                'remove_cover_image' => '1',
            ])
            ->assertSessionHasNoErrors();

        $certificate->refresh();
        $this->assertNotSame($firstPdf, $certificate->file_path);
        $this->assertNull($certificate->cover_image_path);
        $this->assertFalse($certificate->is_active);
        Storage::disk('public')->assertMissing([$firstPdf, $firstCover]);
        Storage::disk('public')->assertExists($certificate->file_path);
    }

    public function test_deleting_a_certificate_removes_its_files(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin)->post(route('admin.certificates.store'), ['name' => 'To delete', 'file' => $this->pdf()]);
        $certificate = Certificate::query()->sole();

        $this->actingAs($admin)
            ->delete(route('admin.certificates.destroy', $certificate))
            ->assertRedirect(route('admin.certificates.index'));

        $this->assertModelMissing($certificate);
        Storage::disk('public')->assertMissing($certificate->file_path);
    }

    public function test_list_marks_the_certificates_shown_on_the_home_page(): void
    {
        Certificate::factory()->count(4)->sequence(fn ($sequence) => ['sort_order' => $sequence->index])->create();

        $response = $this->actingAs(Admin::factory()->create())->get(route('admin.certificates.index'))->assertOk();

        $this->assertSame(Certificate::HOME_LIMIT, substr_count($response->getContent(), 'On home page'));
    }

    public function test_admin_can_edit_the_certificates_page_banner(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.certificates-page.update'), [
                'banner' => ['eyebrow' => 'Verified', 'title' => 'Our Certificates'],
                'banner_background_image' => UploadedFile::fake()->image('banner.jpg', 2000, 600),
            ])
            ->assertRedirect(route('admin.certificates-page.edit'))
            ->assertSessionHasNoErrors();

        $banner = app(PageSectionService::class)->section(CertificatesPageSection::Banner);
        $this->assertSame('Our Certificates', $banner['title']);
        $this->assertStringStartsWith('certificates/', $banner['background_image']);

        $this->get(route('certificates'))->assertSee('Our Certificates')->assertSee($banner['background_image_url']);
    }

    private function pdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('certificate.pdf', "%PDF-1.4\n%test certificate\n");
    }
}
