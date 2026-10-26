<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->orderBy('id', 'desc')->paginate(10);
        return view('admin.blog_posts.list', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::where('status', 'active')->get();
        return view('admin.blog_posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:210|unique:blog_posts,slug',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->except(['featured_image', 'og_image']);
        $data['author_id'] = Auth::check() ? Auth::id() : 1; 

        // Auto set published_at if status is published
        if ($data['status'] == 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadFile($request, 'featured_image', 'uploads/blogs');
        }
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $this->uploadFile($request, 'og_image', 'uploads/blogs/seo');
        }

        BlogPost::create($data);

        return redirect()->route('admin.blog_posts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::where('status', 'active')->get();
        return view('admin.blog_posts.update', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:210|unique:blog_posts,slug,' . $id,
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->except(['featured_image', 'og_image', 'remove_images']);
        $removeImages = $request->input('remove_images', []);

        // Manage published_at timestamp
        if ($data['status'] == 'published' && is_null($post->published_at)) {
            $data['published_at'] = now();
        } elseif ($data['status'] == 'draft') {
            $data['published_at'] = null;
        }

        // Deferred Image Deletion Logic
        if ($request->hasFile('featured_image')) {
            $this->deleteFile($post->featured_image);
            $data['featured_image'] = $this->uploadFile($request, 'featured_image', 'uploads/blogs');
        } elseif (in_array('featured_image', $removeImages)) {
            $this->deleteFile($post->featured_image);
            $data['featured_image'] = null;
        }

        if ($request->hasFile('og_image')) {
            $this->deleteFile($post->og_image);
            $data['og_image'] = $this->uploadFile($request, 'og_image', 'uploads/blogs/seo');
        } elseif (in_array('og_image', $removeImages)) {
            $this->deleteFile($post->og_image);
            $data['og_image'] = null;
        }

        $post->update($data);

        return redirect()->route('admin.blog_posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->deleteFile($post->featured_image);
        $this->deleteFile($post->og_image);
        $post->delete();

        return redirect()->route('admin.blog_posts.index')->with('success', 'Blog post deleted successfully.');
    }

    // --- Helpers ---
    private function uploadFile($request, $fieldName, $path) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = time() . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path($path), $fileName);
            return $path . '/' . $fileName;
        }
        return null;
    }
    private function deleteFile($filePath) {
        if ($filePath && file_exists(public_path($filePath))) unlink(public_path($filePath));
    }
}