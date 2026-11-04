<?php

declare(strict_types=1);

use App\Http\Controllers\Site\HomeController;
use Illuminate\Support\Facades\Route;

/*
| Public website routes. Visitors never log in; every page is read-only
| except the enquiry form, which will be rate limited.
*/

Route::get('/', HomeController::class)->name('home');
