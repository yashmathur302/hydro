<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Client;
use App\Models\ProductCategory;
use App\Models\BlogCategory;

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

        $data = $request->except(['breadcrumb_image']);

        if ($request->hasFile('breadcrumb_image')) {
            $file = $request->file('breadcrumb_image');
            $fileName = time() . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/pages/breadcrumbs'), $fileName);
            $data['breadcrumb_image'] = 'uploads/pages/breadcrumbs/' . $fileName;
        }

        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $fileName = time() . '-cert.' . $file->extension();
            $file->move(public_path('uploads/pages/certificates'), $fileName);
            $data['certificate_file'] = 'uploads/pages/certificates/' . $fileName;
        }

        Page::create($data);

        // Page::create($request->all());
        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }
    
    public function edit($id)
    {
        $page = Page::with(['sections' => function($query) {
            $query->orderBy('sort_order', 'asc');
        }])->findOrFail($id);

        // Fetch items for specific sections dropdowns
        // $products = Product::where('status', 'published')->get(['id', 'name']);
        // $clients = Client::where('status', 'active')->get(['id', 'name']);

        $products = Product::where('status', 'published')->get(['id', 'name']);
        $productCategories = ProductCategory::where('status', 'active')->get(['id', 'name']);
        $clients = Client::where('status', 'active')->get(['id', 'name']);
        $blog_posts = BlogPost::where('status', 'published')->get(['id', 'title']);
        $blogCategories = BlogCategory::where('status', 'active')->get(['id', 'name']);

        return view('admin.pages.update', compact('page', 'products', 'clients', 'blog_posts', 'productCategories', 'blogCategories'));
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

        $data = [
            'title' => $request->title,
            'slug' => $request->slug,
            'page_type' => $request->page_type,
            'content' => $request->page_type === 'home' ? null : $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ];

        $removeImages = $request->input('remove_images', []);

        if ($request->hasFile('breadcrumb_image')) {
            if ($page->breadcrumb_image && file_exists(public_path($page->breadcrumb_image))) {
                unlink(public_path($page->breadcrumb_image));
            }
            $file = $request->file('breadcrumb_image');
            $fileName = time() . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/pages/breadcrumbs'), $fileName);
            $data['breadcrumb_image'] = 'uploads/pages/breadcrumbs/' . $fileName;
        } elseif (in_array('breadcrumb_image', $removeImages)) {
            if ($page->breadcrumb_image && file_exists(public_path($page->breadcrumb_image))) {
                unlink(public_path($page->breadcrumb_image));
            }
            $data['breadcrumb_image'] = null;
        }

        // Certificate File (Image or PDF) for Inner Page
        if ($request->hasFile('certificate_file')) {
            if ($page->certificate_file && file_exists(public_path($page->certificate_file))) {
                unlink(public_path($page->certificate_file));
            }
            $file = $request->file('certificate_file');
            $fileName = time() . '-cert.' . $file->extension();
            $file->move(public_path('uploads/pages/certificates'), $fileName);
            $data['certificate_file'] = 'uploads/pages/certificates/' . $fileName;
        } elseif (in_array('certificate_file', $removeImages)) {
            if ($page->certificate_file && file_exists(public_path($page->certificate_file))) {
                unlink(public_path($page->certificate_file));
            }
            $data['certificate_file'] = null;
        }


        $page->update($data);

        // $page->update([
        //     'title' => $request->title,
        //     'slug' => $request->slug,
        //     'page_type' => $request->page_type,
        //     // Agar page_type home hai, toh content null kar do taaki garbage data save na ho
        //     'content' => $request->page_type === 'home' ? null : $request->content,
        //     'meta_title' => $request->meta_title,
        //     'meta_description' => $request->meta_description,
        // ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        
        // Page delete hote hi database ke foreign key cascade rules ke karan 
        // iske saare sections bhi automatically delete ho jayenge.
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page and its sections deleted successfully.');
    }
}
