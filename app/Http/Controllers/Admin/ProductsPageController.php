<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ProductsPageSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductsPageRequest;
use App\Services\PageSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ProductsPageController extends Controller
{
    public function edit(PageSectionService $pageSections): View
    {
        return view('admin.products-page.edit', [
            'banner' => $pageSections->section(ProductsPageSection::Banner),
            'intro' => $pageSections->section(ProductsPageSection::Intro),
        ]);
    }

    public function update(UpdateProductsPageRequest $request, PageSectionService $pageSections): RedirectResponse
    {
        foreach (ProductsPageSection::cases() as $section) {
            $pageSections->save(
                $section,
                $request->textFor($section),
                $request->uploadsFor($section),
                $request->removalsFor($section),
            );
        }

        return redirect()->route('admin.products-page.edit')->with('status', 'Products page saved.');
    }
}
