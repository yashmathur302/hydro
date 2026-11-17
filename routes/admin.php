<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\CertificatesPageController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContactPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductsPageController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('categories', ProductCategoryController::class)
        ->except('show')
        ->parameters(['categories' => 'category']);

    Route::get('enquiries', [EnquiryController::class, 'index'])->defaults('type', 'product')->name('enquiries.index');
    Route::get('contact-messages', [EnquiryController::class, 'index'])->defaults('type', 'contact')->name('contact-messages.index');
    Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->whereNumber('enquiry')->name('enquiries.show');
    Route::put('enquiries/{enquiry}', [EnquiryController::class, 'update'])->whereNumber('enquiry')->name('enquiries.update');
    Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->whereNumber('enquiry')->name('enquiries.destroy');
    Route::post('enquiries/{enquiry}/reveal-sender', [EnquiryController::class, 'revealSender'])
        ->whereNumber('enquiry')->middleware('throttle:30,1')->name('enquiries.reveal-sender');

    Route::get('home-page', [HomePageController::class, 'edit'])->name('home-page.edit');
    Route::put('home-page', [HomePageController::class, 'update'])->name('home-page.update');
    Route::get('header-footer', [LayoutController::class, 'edit'])->name('layout.edit');
    Route::put('header-footer', [LayoutController::class, 'update'])->name('layout.update');
    Route::get('contact-page', [ContactPageController::class, 'edit'])->name('contact-page.edit');
    Route::put('contact-page', [ContactPageController::class, 'update'])->name('contact-page.update');
    Route::get('about-page', [AboutPageController::class, 'edit'])->name('about-page.edit');
    Route::put('about-page', [AboutPageController::class, 'update'])->name('about-page.update');

    Route::resource('industries', IndustryController::class)->except('show');
    Route::resource('clients', ClientController::class)->except('show');
    Route::resource('certificates', CertificateController::class)->except('show');
    Route::get('products-page', [ProductsPageController::class, 'edit'])->name('products-page.edit');
    Route::put('products-page', [ProductsPageController::class, 'update'])->name('products-page.update');
    Route::get('certificates-page', [CertificatesPageController::class, 'edit'])->name('certificates-page.edit');
    Route::put('certificates-page', [CertificatesPageController::class, 'update'])->name('certificates-page.update');

    Route::resource('pages', PageController::class)->except('show');

    Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');

    Route::get('account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('account/password', [AccountController::class, 'updatePassword'])
        ->middleware('throttle:admin-password-change')
        ->name('account.password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
