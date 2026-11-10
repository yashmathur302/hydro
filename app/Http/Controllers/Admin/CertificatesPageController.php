<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\CertificatesPageSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCertificatesPageRequest;
use App\Services\PageSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CertificatesPageController extends Controller
{
    public function edit(PageSectionService $pageSections): View
    {
        return view('admin.certificates-page.edit', [
            'banner' => $pageSections->section(CertificatesPageSection::Banner),
        ]);
    }

    public function update(UpdateCertificatesPageRequest $request, PageSectionService $pageSections): RedirectResponse
    {
        foreach (CertificatesPageSection::cases() as $section) {
            $pageSections->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.certificates-page.edit')->with('status', 'Certificates page banner saved.');
    }
}
