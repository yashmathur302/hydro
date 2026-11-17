<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ContactPageSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactPageRequest;
use App\Services\PageSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ContactPageController extends Controller
{
    public function edit(PageSectionService $pageSections): View
    {
        return view('admin.contact-page.edit', [
            'banner' => $pageSections->section(ContactPageSection::Banner),
            'cards' => $pageSections->section(ContactPageSection::Cards),
            'form' => $pageSections->section(ContactPageSection::Form),
            'map' => $pageSections->section(ContactPageSection::Map),
        ]);
    }

    public function update(UpdateContactPageRequest $request, PageSectionService $pageSections): RedirectResponse
    {
        foreach (ContactPageSection::cases() as $section) {
            $pageSections->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.contact-page.edit')->with('status', 'Contact Us page saved.');
    }
}
