<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('id', 'desc')->paginate(10);
        return view('admin.blog_categories.list', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:160|unique:blog_categories,slug',
            'status' => 'required|in:active,inactive',
        ]);

        BlogCategory::create($request->all());

        return response()->json(['success' => true, 'message' => 'Blog Category created successfully.']);
    }

    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:160|unique:blog_categories,slug,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($request->all());

        return response()->json(['success' => true, 'message' => 'Blog Category updated successfully.']);
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category deleted successfully.');
    }
}