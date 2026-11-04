<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(SeoMetaFactory $seo): View
    {
        return view('site.home', ['seo' => $seo->make()]);
    }
}
