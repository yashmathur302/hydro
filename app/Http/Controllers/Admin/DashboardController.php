<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Enums\SiteSettingKey;
use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\SiteSettingService;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    private const RECENT_ENQUIRIES = 5;

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

    public function __invoke(SiteSettingService $settings): View
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
            'productStats' => $this->productStats(),
            'enquiryStats' => $this->enquiryStats(),
            'recentEnquiries' => Enquiry::query()->latest()->latest('id')->limit(self::RECENT_ENQUIRIES)->get(),
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function productStats(): array
    {
        $row = Product::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured')
            ->toBase()
            ->first();

        return [
            'total' => (int) $row->total,
            'active' => (int) $row->active,
            'inactive' => (int) $row->total - (int) $row->active,
            'featured' => (int) $row->featured,
            'categories' => ProductCategory::query()->count(),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function enquiryStats(): array
    {
        $row = Enquiry::query()
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as open', [EnquiryStatus::New->value])
            ->selectRaw('SUM(CASE WHEN type = ? THEN 1 ELSE 0 END) as products', [EnquiryType::Product->value])
            ->selectRaw('SUM(CASE WHEN type = ? THEN 1 ELSE 0 END) as contacts', [EnquiryType::Contact->value])
            ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as last_seven_days', [now()->subDays(7)])
            ->toBase()
            ->first();

        return [
            'new' => (int) $row->open,
            'products' => (int) $row->products,
            'contacts' => (int) $row->contacts,
            'lastSevenDays' => (int) $row->last_seven_days,
        ];
    }
}
