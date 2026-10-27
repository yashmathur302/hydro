<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Client;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Fetch home page with active sections sorted by order
        $page = Page::with(['sections' => function($query) {
            $query->where('status', 'active')->orderBy('sort_order', 'asc');
        }])->where('page_type', 'home')->firstOrFail();

        // 2. Loop through sections and attach dynamic data if needed
        foreach ($page->sections as $section) {
            
            // Convert JSON settings to array if it's not already casted
            $settings = is_string($section->settings) ? json_decode($section->settings, true) : ($section->settings ?? []);

            // --- Dynamic Products Fetching ---
            if ($section->section_type === 'products') {
                $query = Product::with('category')->where('status', 'published');
                
                // Specific IDs rule over categories
                if (!empty($settings['product_ids'])) {
                    $query->whereIn('id', $settings['product_ids']);
                } elseif (!empty($settings['product_category']) && $settings['product_category'] !== 'all') {
                    $query->where('category_id', $settings['product_category']);
                }
                
                // Sorting
                $sort = $settings['product_sort'] ?? 'desc';
                if ($sort === 'featured') {
                    $query->where('is_featured', 1)->orderBy('id', 'desc');
                } else {
                    $query->orderBy('id', $sort);
                }
                
                $limit = $settings['product_limit'] ?? 4;
                // Attach dynamic data to the section object itself
                $section->dynamic_products = $query->take($limit)->get();
            }

            // --- Dynamic Blogs Fetching ---
            if ($section->section_type === 'blogs') {
                $query = BlogPost::with(['category', 'author'])->where('status', 'published');
                
                if (!empty($settings['blog_ids'])) {
                    $query->whereIn('id', $settings['blog_ids']);
                } elseif (!empty($settings['blog_category']) && $settings['blog_category'] !== 'all') {
                    $query->where('category_id', $settings['blog_category']);
                }
                
                $sort = $settings['blog_sort'] ?? 'desc';
                $query->orderBy('id', $sort);
                
                $limit = $settings['blog_limit'] ?? 3;
                $section->dynamic_blogs = $query->take($limit)->get();
            }

            // --- Dynamic Clients / Certificates Fetching ---
            if ($section->section_type === 'clients') {
                $query = Client::where('status', 'active');
                
                if (!empty($settings['client_ids'])) {
                    $query->whereIn('id', $settings['client_ids']);
                }
                
                $section->dynamic_clients = $query->get();
            }
        }

        return view('frontend.home', compact('page'));
    }
}