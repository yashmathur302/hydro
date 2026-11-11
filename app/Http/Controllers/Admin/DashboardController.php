<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Services\DashboardStatistics;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    private const RECENT_ENQUIRIES = 7;

    public function index(DashboardStatistics $statistics): View
    {
        return view('admin.dashboard', [
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
