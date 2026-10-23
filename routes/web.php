<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('pages', PageController::class);

    Route::resource('page-sections', PageSectionController::class)->except(['index', 'create', 'show']);
    Route::post('page-sections/update-order', [PageSectionController::class, 'updateOrder'])->name('page-sections.updateOrder');
});
