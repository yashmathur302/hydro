<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\SendPasswordResetLinkRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Throwable;

final class PasswordResetLinkController extends Controller
{
    private const GENERIC_MESSAGE = 'If that email address belongs to the admin account, a password reset link has been sent to it.';

    public function create(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Always answers with the same message, so the form cannot be used to find
     * out which email address belongs to the admin. A mail server failure is
     * logged instead of showing an error page (which would also reveal that
     * the address exists).
     */
    public function store(SendPasswordResetLinkRequest $request): RedirectResponse
    {
        try {
            Password::broker('admins')->sendResetLink($request->only('email'));
        } catch (Throwable $exception) {
            Log::error('Password reset email could not be sent. Check the MAIL_ settings in .env.', [
                'error' => $exception->getMessage(),
            ]);
        }

        return back()->with('status', self::GENERIC_MESSAGE);
    }
}
