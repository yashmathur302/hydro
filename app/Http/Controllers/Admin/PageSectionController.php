<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'section_type' => 'required|string|max:50',
            'title' => 'nullable|string|max:200',
        ]);

        // Naye section ka sort_order sabse last me rakhne ke liye
        $lastOrder = PageSection::where('page_id', $request->page_id)->max('sort_order');
        
        PageSection::create([
            'page_id' => $request->page_id,
            'section_type' => $request->section_type,
            'title' => $request->title,
            'sort_order' => $lastOrder ? $lastOrder + 1 : 1,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Section added successfully. You can now edit it.');
    }

    // Fetch Single Section Data for Edit Modal (AJAX)
    public function show(int $id)
    {
        $section = PageSection::findOrFail($id);
        return response()->json(['success' => true, 'data' => $section]);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:200',
            'subtitle' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $section = PageSection::findOrFail($id);
        $data = $request->except(['image', 'settings_product_ids', 'settings_client_ids']);

        // Image Handling
        if ($request->hasFile('image')) {
            if ($section->image && file_exists(public_path($section->image))) {
                unlink(public_path($section->image));
            }
            $imageName = time() . '-' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/sections'), $imageName);
            $data['image'] = 'uploads/sections/' . $imageName;
        }

        // JSON Settings Handling for Arrays
        $settings = $section->settings ?? [];
        
        if ($request->has('settings_product_ids')) {
            $settings['product_ids'] = $request->settings_product_ids; // Array of IDs
        }
        if ($request->has('settings_client_ids')) {
            $settings['client_ids'] = $request->settings_client_ids; // Array of IDs
        }
        
        $data['settings'] = $settings;
        $section->update($data);

        return response()->json(['success' => true, 'message' => 'Section updated successfully.']);
    }

    // Update Section Data (AJAX)
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'title' => 'nullable|string|max:200',
    //         'subtitle' => 'nullable|string|max:255',
    //         'status' => 'required|in:active,inactive',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    //         'button_text' => 'nullable|string|max:50',
    //         'button_link' => 'nullable|string|max:255',
    //     ]);

    //     $section = PageSection::findOrFail($id);
        
    //     $data = $request->except(['image', 'settings_limit']);

    //     // Image Upload Logic
    //     if ($request->hasFile('image')) {
    //         // Delete old image if exists
    //         if ($section->image && file_exists(public_path($section->image))) {
    //             unlink(public_path($section->image));
    //         }
    //         $imageName = time() . '-' . uniqid() . '.' . $request->image->extension();
    //         $request->image->move(public_path('uploads/sections'), $imageName);
    //         $data['image'] = 'uploads/sections/' . $imageName;
    //     }

    //     // JSON Settings Handle Karna (e.g., limit for products/blogs)
    //     $settings = $section->settings ?? [];
    //     if ($request->has('settings_limit')) {
    //         $settings['limit'] = $request->settings_limit;
    //     }
    //     $data['settings'] = $settings;

    //     $section->update($data);

    //     return response()->json(['success' => true, 'message' => 'Section updated successfully.']);
    // }

    // Drag & Drop Sort Update (AJAX)
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:page_sections,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            PageSection::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Section order updated.']);
    }

    public function destroy($id)
    {
        $section = PageSection::findOrFail($id);
        $section->delete();
        
        // AJAX request ke liye JSON response return kar rahe hain
        return response()->json([
            'success' => true, 
            'message' => 'Section removed successfully.'
        ]);
    }
}
