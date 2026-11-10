<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\CertificatesPageSection;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\PageSectionService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

final class CertificatesController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, PageSectionService $pageSections): View
    {
        $banner = $pageSections->section(CertificatesPageSection::Banner);

        return view('site.certificates', [
            'seo' => $seo->make(title: $banner['title'], imageUrl: $banner['background_image_url']),
            'banner' => $banner,
            'certificates' => Certificate::query()->active()->ordered()->get(),
        ]);
    }
}
