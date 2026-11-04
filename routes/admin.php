<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

/*
| Admin panel routes, loaded with the "web" middleware group, the "/admin"
| prefix and the "admin." name prefix (see bootstrap/app.php).
*/

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:admin-login')
        ->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:admin-password-reset')
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->middleware('throttle:admin-password-reset')
        ->name('password.store');
});

Route::middleware(['auth', 'auth.session'])->group(function (): void {
    Route::redirect('/', '/admin/dashboard')->name('home');
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');

    Route::get('account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('account/password', [AccountController::class, 'updatePassword'])
        ->middleware('throttle:admin-password-change')
        ->name('account.password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
