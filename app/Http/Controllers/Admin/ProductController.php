<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        // 10 data limit (Pagination) add kar di gayi hai
        $products = Product::with('category')->orderBy('id', 'desc')->paginate(10);
        return view('admin.products.list', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::where('status', 'active')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'slug' => 'required|string|max:210|unique:products,slug',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['thumbnail', 'brochure_file', 'og_image', 'gallery_images', 'spec_keys', 'spec_values']);
        
        // Logged-in user id (Auth bypass hone par 1 default)
        $data['created_by'] = Auth::check() ? Auth::id() : 1; 
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $data['thumbnail'] = $this->uploadFile($request, 'thumbnail', 'uploads/products/thumbnails');
        $data['brochure_file'] = $this->uploadFile($request, 'brochure_file', 'uploads/products/brochures');
        $data['og_image'] = $this->uploadFile($request, 'og_image', 'uploads/products/seo');
        $data['specifications'] = $this->mapSpecifications($request);

        $product = Product::create($data);

        // Multiple Gallery Images upload to product_images table
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $imageName = time() . '-' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/products/gallery'), $imageName);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'uploads/products/gallery/' . $imageName,
                    'created_at' => now()
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = ProductCategory::where('status', 'active')->get();
        return view('admin.products.update', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'slug' => 'required|string|max:210|unique:products,slug,' . $id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['thumbnail', 'brochure_file', 'og_image', 'gallery_images', 'spec_keys', 'spec_values', 'remove_images', 'remove_gallery_images']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        // --- Deferred Image Deletion Logic (Hidden Inputs se data aayega) ---
        $removeImages = $request->input('remove_images', []); 
        $removeGalleryIds = $request->input('remove_gallery_images', []);

        // 1. Thumbnail Upload or Remove
        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($product->thumbnail);
            $data['thumbnail'] = $this->uploadFile($request, 'thumbnail', 'uploads/products/thumbnails');
        } elseif (in_array('thumbnail', $removeImages)) {
            $this->deleteFile($product->thumbnail);
            $data['thumbnail'] = null;
        }

        // 2. OG Image Upload or Remove
        if ($request->hasFile('og_image')) {
            $this->deleteFile($product->og_image);
            $data['og_image'] = $this->uploadFile($request, 'og_image', 'uploads/products/seo');
        } elseif (in_array('og_image', $removeImages)) {
            $this->deleteFile($product->og_image);
            $data['og_image'] = null;
        }

        // 3. Brochure File
        if ($request->hasFile('brochure_file')) {
            $this->deleteFile($product->brochure_file);
            $data['brochure_file'] = $this->uploadFile($request, 'brochure_file', 'uploads/products/brochures');
        } elseif (in_array('brochure_file', $removeImages)) {
            $this->deleteFile($product->brochure_file);
            $data['brochure_file'] = null;
        }

        // 4. Gallery Images Deletion (from secondary table)
        if (!empty($removeGalleryIds)) {
            $imagesToDelete = ProductImage::whereIn('id', $removeGalleryIds)->get();
            foreach ($imagesToDelete as $img) {
                $this->deleteFile($img->image);
                $img->delete();
            }
        }

        // Save Basic Data
        $data['specifications'] = $this->mapSpecifications($request);
        $product->update($data);

        // Upload New Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $imageName = time() . '-' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/products/gallery'), $imageName);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'uploads/products/gallery/' . $imageName,
                    'created_at' => now()
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        $this->deleteFile($product->thumbnail);
        $this->deleteFile($product->brochure_file);
        $this->deleteFile($product->og_image);
        
        // Delete gallery images from server first
        foreach($product->images as $img) {
            $this->deleteFile($img->image);
        }
        
        $product->delete(); // Cascade rule chalega aur DB se bhi images delete ho jayengi
        return redirect()->route('admin.products.index')->with('success', 'Product deleted permanently.');
    }

    // AJAX Method for individual gallery image delete
    public function deleteGalleryImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $this->deleteFile($image->image);
        $image->delete();
        return response()->json(['success' => true, 'message' => 'Image removed permanently.']);
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
    private function mapSpecifications($request) {
        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key)) $specs[$key] = $request->spec_values[$index] ?? '';
            }
        }
        return empty($specs) ? null : $specs;
    }
}