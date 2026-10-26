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

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:200',
            'subtitle' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'image_primary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_secondary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        $section = PageSection::findOrFail($id);
        $data = $request->except(['image_primary', 'image_secondary', 'settings', 'section_type', 'page_id', 'remove_images', '_token', '_method']);

        // --- Associative Array Logic for Images (Handling Replacement & Removal) ---
        $existingImages = is_array($section->image) ? $section->image : [];
        if (is_string($section->image) && !empty($section->image)) {
            $existingImages = ['primary' => $section->image];
        }

        $removeImages = $request->input('remove_images', []); // Frontend se aayega agar admin image hatayega

        // Primary Image Logic
        if ($request->hasFile('image_primary')) {
            // Delete old if exists (Replacement fix)
            if (isset($existingImages['primary']) && file_exists(public_path($existingImages['primary']))) {
                unlink(public_path($existingImages['primary']));
            }
            $file = $request->file('image_primary');
            $imageName = time() . '-1.' . $file->extension();
            $file->move(public_path('uploads/sections'), $imageName);
            $existingImages['primary'] = 'uploads/sections/' . $imageName; 
        } elseif (in_array('primary', $removeImages)) {
            // User removed the image and didn't upload a new one
            if (isset($existingImages['primary']) && file_exists(public_path($existingImages['primary']))) {
                unlink(public_path($existingImages['primary']));
            }
            unset($existingImages['primary']);
        }

        // Secondary Image Logic
        if ($request->hasFile('image_secondary')) {
            // Delete old if exists (Replacement fix)
            if (isset($existingImages['secondary']) && file_exists(public_path($existingImages['secondary']))) {
                unlink(public_path($existingImages['secondary']));
            }
            $file = $request->file('image_secondary');
            $imageName = time() . '-2.' . $file->extension();
            $file->move(public_path('uploads/sections'), $imageName);
            $existingImages['secondary'] = 'uploads/sections/' . $imageName; 
        } elseif (in_array('secondary', $removeImages)) {
            // User removed the image and didn't upload a new one
            if (isset($existingImages['secondary']) && file_exists(public_path($existingImages['secondary']))) {
                unlink(public_path($existingImages['secondary']));
            }
            unset($existingImages['secondary']);
        }

        $data['image'] = empty($existingImages) ? null : $existingImages;

        // --- Settings Logic ---
        $settings = $section->settings ?? [];
        $type = $section->section_type; 
        
        // Products Settings (Limit add kiya gaya)
        if ($type === 'products') {
            $settings['product_ids'] = $request->input('settings.product_ids', []);
            $settings['product_category'] = $request->input('settings.product_category', 'all');
            $settings['product_sort'] = $request->input('settings.product_sort', 'desc');
            $settings['product_limit'] = $request->input('settings.product_limit', 4); // New
        }
        
        // Blogs Settings (Specific Blogs IDs add kiya gaya)
        if ($type === 'blogs') {
            $settings['blog_ids'] = $request->input('settings.blog_ids', []); // New
            $settings['blog_category'] = $request->input('settings.blog_category', 'all');
            $settings['blog_sort'] = $request->input('settings.blog_sort', 'desc');
            $settings['blog_limit'] = $request->input('settings.blog_limit', 3);
        }

        if ($type === 'clients') {
            $settings['client_ids'] = $request->input('settings.client_ids', []);
        }

        $data['settings'] = $settings;
        $section->update($data);

        return response()->json(['success' => true, 'message' => 'Section updated successfully.']);
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'title' => 'nullable|string|max:200',
    //         'subtitle' => 'nullable|string|max:255',
    //         'status' => 'required|in:active,inactive',
    //         'image_primary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    //         'image_secondary' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    //         'button_text' => 'nullable|string|max:50',
    //         'button_link' => 'nullable|string|max:255',
    //     ]);

    //     $section = PageSection::findOrFail($id);
    //     $data = $request->except(['image_primary', 'image_secondary', 'settings', 'section_type', 'page_id', '_token', '_method']);

    //     // --- Associative Array Logic for Images ---
    //     $existingImages = is_array($section->image) ? $section->image : [];
        
    //     // Agar purani single string image DB me padi hai, usko array me convert karlo safely
    //     if (is_string($section->image) && !empty($section->image)) {
    //         $existingImages = ['primary' => $section->image];
    //     }

    //     if ($request->hasFile('image_primary')) {
    //         $file = $request->file('image_primary');
    //         $imageName = time() . '-1.' . $file->extension();
    //         $file->move(public_path('uploads/sections'), $imageName);
    //         $existingImages['primary'] = 'uploads/sections/' . $imageName; 
    //     }

    //     if ($request->hasFile('image_secondary')) {
    //         $file = $request->file('image_secondary');
    //         $imageName = time() . '-2.' . $file->extension();
    //         $file->move(public_path('uploads/sections'), $imageName);
    //         $existingImages['secondary'] = 'uploads/sections/' . $imageName; 
    //     }

    //     $data['image'] = empty($existingImages) ? null : $existingImages;

    //     // --- Settings Logic ---
    //     $settings = $section->settings ?? [];
    //     $type = $section->section_type; 
        
    //     if ($type === 'products') {
    //         $settings['product_ids'] = $request->input('settings.product_ids', []);
    //         $settings['product_category'] = $request->input('settings.product_category', 'all');
    //         $settings['product_sort'] = $request->input('settings.product_sort', 'desc');
    //     }
    //     if ($type === 'blogs') {
    //         $settings['blog_category'] = $request->input('settings.blog_category', 'all');
    //         $settings['blog_sort'] = $request->input('settings.blog_sort', 'desc');
    //         $settings['blog_limit'] = $request->input('settings.blog_limit', 3);
    //     }
    //     if ($type === 'clients') {
    //         $settings['client_ids'] = $request->input('settings.client_ids', []);
    //     }
    //     if ($type === 'cta') {
    //         $settings['cta_layout'] = $request->input('settings.cta_layout', 'center');
    //         $settings['cta_bg_color'] = $request->input('settings.cta_bg_color', '#ffffff');
    //     }

    //     $data['settings'] = $settings;
    //     $section->update($data);

    //     return response()->json(['success' => true, 'message' => 'Section updated successfully.']);
    // }

    public function deleteImage(Request $request, $id)
    {
        $section = PageSection::findOrFail($id);
        $key = $request->image_key; // 'primary' ya 'secondary' aayega frontend se
        
        $images = is_array($section->image) ? $section->image : [];
        if (is_string($section->image) && !empty($section->image) && $key === 'primary') {
            $images = ['primary' => $section->image];
        }

        if (isset($images[$key])) {
            if (file_exists(public_path($images[$key]))) {
                unlink(public_path($images[$key]));
            }
            unset($images[$key]);
            $section->update(['image' => empty($images) ? null : array_values($images)]); // array_values if you want reset, but associative is better kept as is
            $section->update(['image' => empty($images) ? null : $images]); 
            return response()->json(['success' => true, 'message' => 'Image removed permanently.']);
        }

        return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
    }

    // public function update(Request $request, int $id)
    // {
    //     $request->validate([
    //         'title' => 'nullable|string|max:200',
    //         'subtitle' => 'nullable|string|max:255',
    //         'status' => 'required|in:active,inactive',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    //     ]);

    //     $section = PageSection::findOrFail($id);
    //     $data = $request->except(['image', 'settings_product_ids', 'settings_client_ids']);

    //     // Image Handling
    //     if ($request->hasFile('image')) {
    //         if ($section->image && file_exists(public_path($section->image))) {
    //             unlink(public_path($section->image));
    //         }
    //         $imageName = time() . '-' . uniqid() . '.' . $request->image->extension();
    //         $request->image->move(public_path('uploads/sections'), $imageName);
    //         $data['image'] = 'uploads/sections/' . $imageName;
    //     }

    //     // JSON Settings Handling for Arrays
    //     $settings = $section->settings ?? [];
        
    //     if ($request->has('settings_product_ids')) {
    //         $settings['product_ids'] = $request->settings_product_ids; // Array of IDs
    //     }
    //     if ($request->has('settings_client_ids')) {
    //         $settings['client_ids'] = $request->settings_client_ids; // Array of IDs
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
