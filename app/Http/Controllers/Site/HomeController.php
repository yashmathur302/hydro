<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\HomeSection;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Industry;
use App\Services\HomePageService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, HomePageService $homePage): View
    {
        $hero = $homePage->section(HomeSection::Hero);

        return view('site.home', [
            'seo' => $seo->make(imageUrl: $hero['background_image_url']),
            'hero' => $hero,
            'about' => $homePage->section(HomeSection::About),
            'industriesSection' => $homePage->section(HomeSection::Industries),
            'industries' => Industry::query()->active()->ordered()->get(),
            'stats' => $homePage->section(HomeSection::Stats),
            'whyUs' => $homePage->section(HomeSection::WhyUs),
            'clients' => Client::query()->active()->ordered()->get(),
        ]);
    }
}
