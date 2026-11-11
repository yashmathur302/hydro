<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryStatus;
use App\Models\Admin;
use App\Models\Enquiry;
use App\Models\Product;
use App\Services\DashboardStatistics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_read_the_dashboard_or_its_statistics(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->getJson(route('admin.dashboard.statistics'))->assertUnauthorized();
    }

    public function test_dashboard_shows_live_figures_and_embeds_chart_data(): void
    {
        $this->actingAs(Admin::factory()->create());
        Enquiry::factory()->count(3)->create();

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('data-dashboard-data', false)
            ->assertSee(route('admin.dashboard.statistics'), false);
    }

    public function test_statistics_endpoint_returns_counts_from_the_database(): void
    {
        $this->actingAs(Admin::factory()->create());

        Product::factory()->count(2)->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);

        Enquiry::factory()->count(2)->create(['product_name' => 'Gauging Pig']);
        Enquiry::factory()->create(['product_name' => 'Brush Pig', 'status' => EnquiryStatus::Closed]);
        Enquiry::factory()->contact()->create(['status' => EnquiryStatus::InProgress]);
        Enquiry::factory()->create(['product_name' => 'Old Pig', 'created_at' => now()->subDays(120)]);

        $response = $this->getJson(route('admin.dashboard.statistics'))
            ->assertOk()
            ->assertJsonStructure([
                'totals' => ['newEnquiries', 'lastSevenDays', 'allEnquiries', 'products', 'activeProducts', 'hiddenProducts'],
                'trend' => [['date', 'label', 'product', 'contact']],
                'statuses' => [['status', 'label', 'count']],
                'topProducts',
                'generatedAt',
            ])
            ->assertJsonPath('totals.allEnquiries', 5)
            ->assertJsonPath('totals.newEnquiries', 3)
            ->assertJsonPath('totals.lastSevenDays', 4)
            ->assertJsonPath('totals.contactMessages', 1)
            ->assertJsonPath('totals.products', 3)
            ->assertJsonPath('totals.activeProducts', 2)
            ->assertJsonPath('totals.hiddenProducts', 1)
            ->assertJsonPath('statuses.0', ['status' => 'new', 'label' => EnquiryStatus::New->label(), 'count' => 3])
            ->assertJsonPath('statuses.1.count', 1)
            ->assertJsonPath('statuses.2.count', 1)
            ->assertJsonPath('topProducts', [
                ['name' => 'Gauging Pig', 'count' => 2],
                ['name' => 'Brush Pig', 'count' => 1],
            ]);

        $trend = $response->json('trend');
        $this->assertCount(DashboardStatistics::TREND_DAYS, $trend);
        $this->assertSame(today()->toDateString(), end($trend)['date']);
        $this->assertSame(3, end($trend)['product']);
        $this->assertSame(1, end($trend)['contact']);
    }
}
