<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AboutSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageRequest;
use App\Services\PageSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AboutPageController extends Controller
{
    public function edit(PageSectionService $pageSections): View
    {
        return view('admin.about-page.edit', [
            'banner' => $pageSections->section(AboutSection::Banner),
        ]);
    }

    public function update(UpdateAboutPageRequest $request, PageSectionService $pageSections): RedirectResponse
    {
        foreach (AboutSection::cases() as $section) {
            $pageSections->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.about-page.edit')->with('status', 'About Us page saved.');
    }
}
