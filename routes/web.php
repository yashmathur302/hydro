<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogCategoryController;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('pages', PageController::class);

    Route::resource('page-sections', PageSectionController::class)->except(['index', 'create']);
    Route::post('page-sections/update-order', [PageSectionController::class, 'updateOrder'])->name('page-sections.updateOrder');
    Route::post('page-sections/{id}/delete-image', [PageSectionController::class, 'deleteImage'])->name('page-sections.deleteImage');

    Route::resource('products', ProductController::class);
    Route::post('product-images/{id}/delete', [ProductController::class, 'deleteGalleryImage'])->name('product-images.delete');
    Route::resource('product_categories', ProductCategoryController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('blog_categories', BlogCategoryController::class);
    Route::resource('blog_posts', BlogPostController::class);

});


// Load Frontend Routes
Route::middleware('web')->group(base_path('routes/frontend.php'));