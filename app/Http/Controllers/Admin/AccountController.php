<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAccountPasswordRequest;
use App\Http\Requests\Admin\UpdateAccountProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

final class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.account.edit', ['admin' => $request->user()]);
    }

    public function updateProfile(UpdateAccountProfileRequest $request): RedirectResponse
    {
        $admin = $request->user();
        $admin->fill($request->validated());
        $emailChanged = $admin->isDirty('email');
        $admin->save();

        if ($emailChanged) {
            Log::channel('security')->notice('Admin email address changed', [
                'admin_id' => $admin->getAuthIdentifier(),
                'ip' => $request->ip(),
            ]);
        }

        return redirect()->route('admin.account.edit')->with('status', 'Profile updated.');
    }

    /**
     * Changing the password also signs out every other browser session.
     */
    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $admin = $request->user();
        $admin->forceFill(['password' => $request->validated('password')])->save();

        Auth::logoutOtherDevices($request->validated('password'));
        $request->session()->regenerate();

        Log::channel('security')->notice('Admin password changed', [
            'admin_id' => $admin->getAuthIdentifier(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.account.edit')->with('status', 'Password changed. Other sessions have been signed out.');
    }
}
