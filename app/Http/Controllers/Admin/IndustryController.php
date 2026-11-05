<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\FeatureIcon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveIndustryRequest;
use App\Models\Industry;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class IndustryController extends Controller
{
    private const IMAGE_DIRECTORY = 'industries';

    private const IMAGE_MAX_WIDTH = 600;

    public function __construct(private readonly WebpImageProcessor $images) {}

    public function index(): View
    {
        return view('admin.industries.index', ['industries' => Industry::query()->ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.industries.form', [
            'industry' => new Industry(['is_active' => true, 'icon' => FeatureIcon::OilGas]),
        ]);
    }

    public function store(SaveIndustryRequest $request): RedirectResponse
    {
        $industry = new Industry($request->industryAttributes());

        if ($image = $request->file('image')) {
            $industry->image_path = $this->images->storeSingle($image, self::IMAGE_DIRECTORY, self::IMAGE_MAX_WIDTH);
        }

        $industry->save();

        return redirect()->route('admin.industries.index')->with('status', "Industry \"{$industry->name}\" added.");
    }

    public function edit(Industry $industry): View
    {
        return view('admin.industries.form', ['industry' => $industry]);
    }

    public function update(SaveIndustryRequest $request, Industry $industry): RedirectResponse
    {
        $previousImage = $industry->image_path;
        $industry->fill($request->industryAttributes());

        if ($image = $request->file('image')) {
            $industry->image_path = $this->images->storeSingle($image, self::IMAGE_DIRECTORY, self::IMAGE_MAX_WIDTH);
        } elseif ($request->boolean('remove_image')) {
            $industry->image_path = null;
        }

        $industry->save();

        if ($previousImage !== null && $previousImage !== $industry->image_path) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect()->route('admin.industries.index')->with('status', "Industry \"{$industry->name}\" saved.");
    }

    public function destroy(Industry $industry): RedirectResponse
    {
        $industry->delete();

        if ($industry->image_path !== null) {
            Storage::disk('public')->delete($industry->image_path);
        }

        return redirect()->route('admin.industries.index')->with('status', "Industry \"{$industry->name}\" deleted.");
    }
}
