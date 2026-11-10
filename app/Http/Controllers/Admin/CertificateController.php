<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveCertificateRequest;
use App\Models\Certificate;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class CertificateController extends Controller
{
    private const FILE_DIRECTORY = 'certificates';

    private const COVER_DIRECTORY = 'certificates/covers';

    private const COVER_MAX_WIDTH = 800;

    public function __construct(private readonly WebpImageProcessor $images) {}

    public function index(): View
    {
        return view('admin.certificates.index', [
            'certificates' => Certificate::query()->ordered()->get(),
            'homeLimit' => Certificate::HOME_LIMIT,
        ]);
    }

    public function create(): View
    {
        return view('admin.certificates.form', ['certificate' => new Certificate(['is_active' => true])]);
    }

    public function store(SaveCertificateRequest $request): RedirectResponse
    {
        $certificate = new Certificate($request->certificateAttributes());
        $certificate->file_path = $this->storePdf($request->file('file'));

        if ($cover = $request->file('cover_image')) {
            $certificate->cover_image_path = $this->images->storeSingle($cover, self::COVER_DIRECTORY, self::COVER_MAX_WIDTH);
        }

        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('status', "Certificate \"{$certificate->name}\" added.");
    }

    public function edit(Certificate $certificate): View
    {
        return view('admin.certificates.form', ['certificate' => $certificate]);
    }

    public function update(SaveCertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $previousFile = $certificate->file_path;
        $previousCover = $certificate->cover_image_path;

        $certificate->fill($request->certificateAttributes());

        if ($file = $request->file('file')) {
            $certificate->file_path = $this->storePdf($file);
        }

        if ($cover = $request->file('cover_image')) {
            $certificate->cover_image_path = $this->images->storeSingle($cover, self::COVER_DIRECTORY, self::COVER_MAX_WIDTH);
        } elseif ($request->boolean('remove_cover_image')) {
            $certificate->cover_image_path = null;
        }

        $certificate->save();

        $replaced = array_filter([
            $previousFile !== $certificate->file_path ? $previousFile : null,
            $previousCover !== $certificate->cover_image_path ? $previousCover : null,
        ]);
        Storage::disk('public')->delete($replaced);

        return redirect()->route('admin.certificates.index')->with('status', "Certificate \"{$certificate->name}\" saved.");
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();
        Storage::disk('public')->delete(array_filter([$certificate->file_path, $certificate->cover_image_path]));

        return redirect()->route('admin.certificates.index')->with('status', "Certificate \"{$certificate->name}\" deleted.");
    }

    /**
     * Stores the PDF under a random name (never the visitor-supplied one).
     */
    private function storePdf(UploadedFile $file): string
    {
        return (string) Storage::disk('public')->putFileAs(self::FILE_DIRECTORY, $file, $file->hashName());
    }
}
