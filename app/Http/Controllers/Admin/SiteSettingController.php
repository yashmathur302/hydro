<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\SiteSettingKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Services\SiteSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

final class SiteSettingController extends Controller
{
    public function edit(SiteSettingService $settings): View
    {
        return view('admin.settings.edit', [
            'settings' => $settings->all(),
            'logoUrl' => $settings->fileUrl(SiteSettingKey::LogoPath),
            'faviconUrl' => $settings->fileUrl(SiteSettingKey::FaviconPath),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, SiteSettingService $settings): RedirectResponse
    {
        $settings->update($request->textSettings());

        $this->syncFile($request, $settings, SiteSettingKey::LogoPath, 'logo', 'remove_logo');
        $this->syncFile($request, $settings, SiteSettingKey::FaviconPath, 'favicon', 'remove_favicon');

        Log::channel('security')->info('Site settings updated', [
            'admin_id' => $request->user()?->getAuthIdentifier(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }

    private function syncFile(
        UpdateSiteSettingsRequest $request,
        SiteSettingService $settings,
        SiteSettingKey $key,
        string $uploadField,
        string $removeField,
    ): void {
        $upload = $request->file($uploadField);

        if ($upload !== null) {
            $settings->replaceFile($key, $upload);
        } elseif ($request->boolean($removeField)) {
            $settings->removeFile($key);
        }
    }
}
