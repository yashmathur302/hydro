<?php

declare(strict_types=1);

use App\Http\Controllers\Site\ContactMessageController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductEnquiryController;
use Illuminate\Support\Facades\Route;

/*
| Public website routes. Visitors never log in; the only write actions are
| the enquiry and contact forms, which are rate limited per IP address.
| Page routes are added as each page design is built.
*/

Route::get('/', HomeController::class)->name('home');

Route::middleware('throttle:enquiries')->group(function (): void {
    Route::post('enquiries', [ProductEnquiryController::class, 'store'])->name('enquiries.store');
    Route::post('contact', [ContactMessageController::class, 'store'])->name('contact.store');
});
