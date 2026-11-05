<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\HomeSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomePageRequest;
use App\Services\HomePageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class HomePageController extends Controller
{
    public function edit(HomePageService $homePage): View
    {
        return view('admin.home-page.edit', [
            'hero' => $homePage->section(HomeSection::Hero),
            'about' => $homePage->section(HomeSection::About),
            'industries' => $homePage->section(HomeSection::Industries),
            'stats' => $homePage->section(HomeSection::Stats),
            'whyUs' => $homePage->section(HomeSection::WhyUs),
        ]);
    }

    public function update(UpdateHomePageRequest $request, HomePageService $homePage): RedirectResponse
    {
        foreach (HomeSection::cases() as $section) {
            $homePage->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.home-page.edit')->with('status', 'Home page saved.');
    }
}
