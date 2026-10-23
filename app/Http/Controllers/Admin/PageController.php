<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('id', 'desc')->get();
        return view('admin.pages.list', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:150',
            'slug' => 'required|unique:pages,slug|max:160',
            'page_type' => 'required|in:home,about,contact,certificates,custom',
        ]);

        Page::create($request->all());

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }
    
    public function edit($id)
    {
        // Page fetch karo aur uske sections ko sort_order ke hisaab se load karo (sirf home page ke liye kaam aayega)
        $page = Page::with(['sections' => function($query) {
            $query->orderBy('sort_order', 'asc');
        }])->findOrFail($id);

        return view('admin.pages.update', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        // Edge Case 1: Slug validation me current page ID ko ignore karna
        $request->validate([
            'title' => 'required|max:150',
            'slug' => 'required|max:160|unique:pages,slug,' . $page->id,
            'page_type' => 'required|in:home,about,contact,certificates,custom',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'page_type' => $request->page_type,
            // Agar page_type home hai, toh content null kar do taaki garbage data save na ho
            'content' => $request->page_type === 'home' ? null : $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }
}
