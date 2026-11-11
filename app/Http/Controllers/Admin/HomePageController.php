<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\HomeSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomePageRequest;
use App\Services\PageSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class HomePageController extends Controller
{
    public function edit(PageSectionService $pageSections): View
    {
        return view('admin.home-page.edit', [
            'hero' => $pageSections->section(HomeSection::Hero),
            'about' => $pageSections->section(HomeSection::About),
            'industries' => $pageSections->section(HomeSection::Industries),
            'stats' => $pageSections->section(HomeSection::Stats),
            'whyUs' => $pageSections->section(HomeSection::WhyUs),
            'certificates' => $pageSections->section(HomeSection::Certificates),
        ]);
    }

    public function update(UpdateHomePageRequest $request, PageSectionService $pageSections): RedirectResponse
    {
        foreach (HomeSection::cases() as $section) {
            $pageSections->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.home-page.edit')->with('status', 'Home page saved.');
    }
}
