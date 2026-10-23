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

    public function destroy($id)
    {
        $section = PageSection::findOrFail($id);
        $section->delete();
        return redirect()->back()->with('success', 'Section removed successfully.');
    }
}
