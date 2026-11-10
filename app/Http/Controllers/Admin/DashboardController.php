<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\SiteSettingKey;
use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Services\DashboardStatistics;
use App\Services\SiteSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    private const RECENT_ENQUIRIES = 6;

    /**
     * Settings the site needs before launch, shown as a setup checklist.
     *
     * @var array<string, string>
     */
    private const SETUP_CHECKLIST = [
        'site_name' => 'Site name',
        'logo_path' => 'Logo',
        'favicon_path' => 'Favicon',
        'contact_phone' => 'Contact phone',
        'contact_email' => 'Contact email',
        'whatsapp_number' => 'WhatsApp number',
        'enquiry_notification_email' => 'Enquiry notification email',
        'default_meta_description' => 'Default meta description',
        'ga4_measurement_id' => 'Google Analytics (GA4)',
        'facebook_pixel_id' => 'Facebook Pixel',
    ];

    public function index(SiteSettingService $settings, DashboardStatistics $statistics): View
    {
        $checklist = collect(self::SETUP_CHECKLIST)->map(
            static fn (string $label, string $key): array => [
                'label' => $label,
                'done' => $settings->get(SiteSettingKey::from($key)) !== null,
            ],
        );

        return view('admin.dashboard', [
            'checklist' => $checklist,
            'completedCount' => $checklist->where('done', true)->count(),
            'statistics' => $statistics->all(),
            'recentEnquiries' => Enquiry::query()->latest()->latest('id')->limit(self::RECENT_ENQUIRIES)->get(),
        ]);
    }

    /**
     * Live figures for the dashboard charts and cards (polled by the dashboard page).
     */
    public function statistics(DashboardStatistics $statistics): JsonResponse
    {
        return response()->json($statistics->all());
    }
}
