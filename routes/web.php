<?php

declare(strict_types=1);

use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\CertificatesController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\ContactMessageController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ProductEnquiryController;
use App\Http\Controllers\Site\StaticPageController;
use App\Models\Page;
use Illuminate\Support\Facades\Route;

/*
| Public website routes. Visitors never log in; the only write actions are
| the enquiry and contact forms, which are rate limited per IP address.
| Page routes are added as each page design is built.
*/

Route::get('/', HomeController::class)->name('home');
Route::get('about-us', AboutController::class)->name('about');
Route::get('certificates', CertificatesController::class)->name('certificates');
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('contact-us', ContactController::class)->name('contact');

Route::middleware('throttle:enquiries')->group(function (): void {
    Route::post('enquiries', [ProductEnquiryController::class, 'store'])->name('enquiries.store');
    Route::post('contact', [ContactMessageController::class, 'store'])->name('contact.store');
});

// Static pages (privacy policy, terms, ...). Must stay last: it matches any
// single-segment path that no route above has claimed. Reserved paths (such
// as /admin, registered after this file) are excluded.
Route::get('{slug}', StaticPageController::class)
    ->where('slug', '(?!(?:'.implode('|', array_map('preg_quote', Page::RESERVED_SLUGS)).')$)[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('pages.show');
