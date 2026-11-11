<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Figures for the admin dashboard, read live from the database. Used for the
 * first render and for the JSON endpoint the dashboard polls to stay current.
 */
final class DashboardStatistics
{
    public const TREND_DAYS = 30;

    public const TOP_PRODUCTS_DAYS = 90;

    public const TOP_PRODUCTS_LIMIT = 5;

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return [
            'totals' => $this->totals(),
            'trend' => $this->dailyTrend(),
            'statuses' => $this->statusBreakdown(),
            'topProducts' => $this->topProducts(),
            'generatedAt' => now()->toIso8601String(),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function totals(): array
    {
        $enquiries = Enquiry::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as open', [EnquiryStatus::New->value])
            ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as recent', [now()->subDays(7)])
            ->selectRaw('SUM(CASE WHEN type = ? THEN 1 ELSE 0 END) as products', [EnquiryType::Product->value])
            ->toBase()
            ->first();

        $products = Product::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured')
            ->toBase()
            ->first();

        return [
            'newEnquiries' => (int) $enquiries->open,
            'lastSevenDays' => (int) $enquiries->recent,
            'allEnquiries' => (int) $enquiries->total,
            'productEnquiries' => (int) $enquiries->products,
            'contactMessages' => (int) $enquiries->total - (int) $enquiries->products,
            'products' => (int) $products->total,
            'activeProducts' => (int) $products->active,
            'hiddenProducts' => (int) $products->total - (int) $products->active,
            'featuredProducts' => (int) $products->featured,
            'categories' => ProductCategory::query()->count(),
        ];
    }

    /**
     * Enquiries per day for the last TREND_DAYS days (today included), with
     * zero-filled days so the chart has a point for every day.
     *
     * @return list<array{date: string, label: string, product: int, contact: int}>
     */
    public function dailyTrend(): array
    {
        $start = today()->subDays(self::TREND_DAYS - 1);

        $rows = Enquiry::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, type, COUNT(*) as aggregate')
            ->groupBy('day', 'type')
            ->toBase()
            ->get();

        $counts = [];

        foreach ($rows as $row) {
            $counts[$row->day][$row->type] = (int) $row->aggregate;
        }

        $trend = [];

        for ($date = $start->copy(); $date->lte(today()); $date->addDay()) {
            $key = $date->toDateString();
            $trend[] = [
                'date' => $key,
                'label' => $date->format('d M'),
                'product' => $counts[$key][EnquiryType::Product->value] ?? 0,
                'contact' => $counts[$key][EnquiryType::Contact->value] ?? 0,
            ];
        }

        return $trend;
    }

    /**
     * @return list<array{status: string, label: string, count: int}>
     */
    public function statusBreakdown(): array
    {
        $counts = Enquiry::query()
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return array_map(
            static fn (EnquiryStatus $status): array => [
                'status' => $status->value,
                'label' => $status->label(),
                'count' => (int) ($counts[$status->value] ?? 0),
            ],
            EnquiryStatus::cases(),
        );
    }

    /**
     * Products with the most enquiries in the last TOP_PRODUCTS_DAYS days.
     *
     * @return list<array{name: string, count: int}>
     */
    public function topProducts(): array
    {
        return Enquiry::query()
            ->where('type', EnquiryType::Product->value)
            ->whereNotNull('product_name')
            ->where('created_at', '>=', Carbon::now()->subDays(self::TOP_PRODUCTS_DAYS))
            ->select('product_name', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('product_name')
            ->orderByDesc('aggregate')
            ->orderBy('product_name')
            ->limit(self::TOP_PRODUCTS_LIMIT)
            ->toBase()
            ->get()
            ->map(static fn (object $row): array => ['name' => (string) $row->product_name, 'count' => (int) $row->aggregate])
            ->all();
    }
}
