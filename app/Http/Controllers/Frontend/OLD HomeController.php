<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch home page with active sections ordered correctly
        $page = Page::with(['sections' => function($query) {
            $query->where('status', 'active')->orderBy('sort_order', 'asc');
        }])->where('page_type', 'home')->firstOrFail();

        return view('frontend.home', compact('page'));
    }
}