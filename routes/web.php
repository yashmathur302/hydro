<?php

declare(strict_types=1);

use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\CertificatesController;
use App\Http\Controllers\Site\ContactMessageController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PlaceholderPageController;
use App\Http\Controllers\Site\ProductEnquiryController;
use Illuminate\Support\Facades\Route;

/*
| Public website routes. Visitors never log in; the only write actions are
| the enquiry and contact forms, which are rate limited per IP address.
| Page routes are added as each page design is built.
*/

Route::get('/', HomeController::class)->name('home');
Route::get('about-us', AboutController::class)->name('about');
Route::get('certificates', CertificatesController::class)->name('certificates');

// Temporary pages until each design is built (replaced by real controllers).
Route::get('products', PlaceholderPageController::class)->defaults('title', 'Products')->name('products.index');
Route::get('contact-us', PlaceholderPageController::class)->defaults('title', 'Contact Us')->name('contact');

Route::middleware('throttle:enquiries')->group(function (): void {
    Route::post('enquiries', [ProductEnquiryController::class, 'store'])->name('enquiries.store');
    Route::post('contact', [ContactMessageController::class, 'store'])->name('contact.store');
});
