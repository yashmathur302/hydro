<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        // 10 Pagination with Parent Relation
        $categories = ProductCategory::with('parent')->orderBy('id', 'desc')->paginate(10);
        return view('admin.product_categories.list', compact('categories'));
    }

    public function create()
    {
        // Fetch all active categories to act as parents
        $parentCategories = ProductCategory::where('status', 'active')->get();
        return view('admin.product_categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:160|unique:product_categories,slug',
            'parent_id' => 'nullable|exists:product_categories,id',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        $data['sort_order'] = $request->sort_order ?? 0;

        ProductCategory::create($data);

        return redirect()->route('admin.product_categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        // Edge Case: Category khud apni parent nahi ban sakti
        $parentCategories = ProductCategory::where('status', 'active')
                                           ->where('id', '!=', $id) // Exclude itself
                                           ->get();
                                           
        return view('admin.product_categories.update', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:160|unique:product_categories,slug,' . $id,
            'parent_id' => 'nullable|exists:product_categories,id',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        // Edge Case Fallback: Agar kisi ne inspect element se khud ki ID bhej di ho
        if ($request->parent_id == $id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
        }

        $data = $request->all();
        $data['sort_order'] = $request->sort_order ?? 0;

        $category->update($data);

        return redirect()->route('admin.product_categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        // Table me foreign key 'ON DELETE SET NULL' hai, to subcategories apne aap parent-less ho jayengi.
        $category->delete();

        return redirect()->route('admin.product_categories.index')->with('success', 'Category deleted successfully.');
    }
}
