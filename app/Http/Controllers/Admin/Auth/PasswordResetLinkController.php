<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\SendPasswordResetLinkRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

final class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Always answers with the same message, so the form cannot be used to
     * find out which email address belongs to the admin.
     */
    public function store(SendPasswordResetLinkRequest $request): RedirectResponse
    {
        Password::broker('admins')->sendResetLink($request->only('email'));

        return back()->with(
            'status',
            'If that email address belongs to the admin account, a password reset link has been sent to it.',
        );
    }
}
