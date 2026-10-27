<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;

// Frontend Routes
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/products', [HomeController::class, 'products'])->name('products');
    Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});