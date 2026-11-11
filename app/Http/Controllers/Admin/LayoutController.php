<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\LayoutSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLayoutRequest;
use App\Services\PageSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Header & Footer editor: the main menu, the call-to-action box above the
 * footer and the footer texts (shown on every page).
 */
final class LayoutController extends Controller
{
    public function edit(PageSectionService $pageSections): View
    {
        return view('admin.layout.edit', [
            'menu' => $pageSections->section(LayoutSection::Menu),
            'cta' => $pageSections->section(LayoutSection::FooterCta),
            'footer' => $pageSections->section(LayoutSection::Footer),
        ]);
    }

    public function update(UpdateLayoutRequest $request, PageSectionService $pageSections): RedirectResponse
    {
        foreach (LayoutSection::cases() as $section) {
            $pageSections->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.layout.edit')->with('status', 'Header and footer saved.');
    }
}
