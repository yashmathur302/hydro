@extends('admin.layouts.master')
@section('content')
{{-- <div class="page-header">
    <div>
        <h4 class="page-title">Edit Product: {{ $product->name }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Edit</a></li>
        </ul>
        <div class="ms-auto">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-round btn-sm">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div> --}}
<div class="page-header">
    <h4 class="page-title">Edit Product</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Create</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div id="removed_images_container"></div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Product Name *</label>
                            <input type="text" class="form-control" name="name" id="title" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>URL Slug *</label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Category</label>
                            <select class="form-select form-control" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>SKU (Stock Keeping Unit)</label>
                            <input type="text" class="form-control" name="sku" value="{{ old('sku', $product->sku) }}">
                        </div>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label>Short Description</label>
                        <textarea class="form-control" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="form-group mt-2">
                        <label>Full Description</label>
                        <textarea class="form-control summernote-editor" name="description">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Dynamic Specifications Builder -->
                    <div class="form-group mt-4 p-3" style="background: #f9fbfd; border: 1px dashed #ccc;">
                        <h5>Specifications</h5>
                        <div id="specifications_wrapper">
                            @if($product->specifications && is_array($product->specifications))
                                @foreach($product->specifications as $key => $value)
                                <div class="row spec-row mb-2">
                                    <div class="col-md-5"><input type="text" class="form-control" name="spec_keys[]" value="{{ $key }}" placeholder="Key"></div>
                                    <div class="col-md-5"><input type="text" class="form-control" name="spec_values[]" value="{{ $value }}" placeholder="Value"></div>
                                    <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-spec"><i class="fa fa-times"></i></button></div>
                                </div>
                                @endforeach
                            @else
                                <div class="row spec-row mb-2">
                                    <div class="col-md-5"><input type="text" class="form-control" name="spec_keys[]" placeholder="e.g., Weight"></div>
                                    <div class="col-md-5"><input type="text" class="form-control" name="spec_values[]" placeholder="e.g., 5 kg"></div>
                                    <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-spec"><i class="fa fa-times"></i></button></div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_spec_btn">Add More Specification</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select form-control" name="status">
                            <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $product->status == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Featured Product</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Show on Homepage</label>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Thumbnail Image</label>
                        <input type="file" class="form-control live-image-upload" name="thumbnail" accept="image/*" data-preview="#thumbnail_preview">
                        <div id="thumbnail_preview" class="mt-2">
                            @if($product->thumbnail)
                            <div class="position-relative d-inline-block image-preview-box mt-2">
                                <img src="{{ asset($product->thumbnail) }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;">
                                <div class="mt-1 d-flex justify-content-between">
                                    <a href="{{ asset($product->thumbnail) }}" target="_blank" class="btn btn-info btn-sm py-0 px-2" title="View Large"><i class="fa fa-eye"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm py-0 px-2 remove-single-image-btn" data-key="thumbnail" title="Remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- BROCHURE -->
                    <div class="form-group mt-2">
                        <label>Brochure (PDF)</label>
                        <input type="file" class="form-control" name="brochure_file" accept=".pdf,.doc,.docx">
                        @if($product->brochure_file)
                            <div class="mt-1 d-flex align-items-center image-preview-box">
                                <a href="{{ asset($product->brochure_file) }}" target="_blank" class="text-info me-3"><i class="fa fa-file-pdf"></i> View Current</a>
                                <button type="button" class="btn btn-danger btn-sm py-0 px-2 remove-single-image-btn" data-key="brochure_file"><i class="fa fa-times"></i></button>
                            </div>
                        @endif
                    </div>

                    <div class="form-group mt-2">
                        <h5>Product Image Gallery</h5>
                        <input type="file" class="form-control live-gallery-upload" name="gallery_images[]" accept="image/*" multiple data-preview="#gallery_preview">
                        
                        <!-- Existing Server Images -->
                        <div id="existing_gallery" class="d-flex flex-wrap mt-3 gap-3">
                            @if(isset($product->images))
                                @foreach($product->images as $img)
                                <div class="position-relative d-inline-block image-preview-box" style="width: 100px;">
                                    <img src="{{ asset($img->image) }}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;">
                                    <div class="mt-1 d-flex justify-content-between">
                                        <a href="{{ asset($img->image) }}" target="_blank" class="btn btn-info btn-sm py-0 px-1" title="View"><i class="fa fa-eye"></i></a>
                                        <button type="button" class="btn btn-danger btn-sm py-0 px-1 remove-gallery-image-btn" data-id="{{ $img->id }}" title="Remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <!-- Live Previews -->
                        <div id="gallery_preview" class="d-flex flex-wrap mt-2 gap-3"></div>
                    </div>

                    <hr>
                    <h5>SEO Settings</h5>
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>

                    <div class="form-group mt-2">
                        <label>OG Image (Social Share)</label>
                        <input type="file" class="form-control live-image-upload" name="og_image" accept="image/*" data-preview="#og_preview">
                        <div id="og_preview" class="mt-2">
                            @if($product->og_image)
                            <div class="position-relative d-inline-block image-preview-box mt-2">
                                <img src="{{ asset($product->og_image) }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;">
                                <div class="mt-1 d-flex justify-content-between">
                                    <a href="{{ asset($product->og_image) }}" target="_blank" class="btn btn-info btn-sm py-0 px-2" title="View Large"><i class="fa fa-eye"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm py-0 px-2 remove-single-image-btn" data-key="og_image" title="Remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-action text-end">
                    <button type="submit" class="btn btn-success">Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
        // --- 1. Auto Slug (Tumhara Purana Code - KEEP) ---
        $('#title').on('keyup', function() {
            var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');   
            $('#slug').val(slug);
        });

        // --- 2. Universal Live Preview (Single Images - NEW) ---
        // Ye Thumbnail aur OG Image dono ka live preview handle karega
        $('.live-image-upload').on('change', function(e) {
            let targetDiv = $(this).data('preview');
            $(targetDiv).empty(); // Purana preview clear karo
            
            if(e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $(targetDiv).html(`
                        <div class="position-relative d-inline-block image-preview-box mt-2">
                            <img src="${e.target.result}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; border: 2px solid #28a745;">
                            <span class="badge badge-success position-absolute" style="top: -5px; right: -5px;">New</span>
                        </div>
                    `);
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // --- 3. Universal Live Preview (Gallery Images - NEW) ---
        // Ye multiple gallery images ka ek sath preview dikhayega
        $('.live-gallery-upload').on('change', function(e) {
            let targetDiv = $(this).data('preview');
            $(targetDiv).empty(); 
            let files = e.target.files;
            
            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $(targetDiv).append(`
                        <div class="position-relative d-inline-block image-preview-box" style="width: 100px;">
                            <img src="${e.target.result}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 5px; border: 2px solid #28a745;">
                            <span class="badge badge-success position-absolute" style="top: -5px; right: -5px;">New</span>
                        </div>
                    `);
                }
                reader.readAsDataURL(files[i]);
            }
        });

        // --- 4. Deferred Deletion: Single Image (Thumbnail / OG Image / Brochure - NEW) ---
        // Cross pe click karte hi UI se hatega aur form me hidden input add hoga
        $(document).on('click', '.remove-single-image-btn', function() {
            let key = $(this).data('key'); // 'thumbnail' ya 'og_image' aayega
            
            // Backend ko batane ke liye hidden input add karo
            $('#removed_images_container').append(`<input type="hidden" name="remove_images[]" value="${key}">`);
            
            // File input ko bhi khali kardo taaki galti se naya wala select ho ke delete na ho jaye
            $(`input[name="${key}"]`).val('');
            
            // UI preview ko fade out karke hata do (No confirmation box)
            $(this).closest('.image-preview-box').fadeOut(300, function(){ $(this).remove(); });
        });

        // --- 5. Deferred Deletion: Gallery Images (NEW - Replaced Old AJAX) ---
        // Purani existing gallery image ko hatane ke liye
        $(document).on('click', '.remove-gallery-image-btn', function() {
            let id = $(this).data('id'); // Image DB ID
            
            // product_images table se delete karne ke liye hidden id add karo
            $('#removed_images_container').append(`<input type="hidden" name="remove_gallery_images[]" value="${id}">`);
            
            // UI preview ko fade out karke hata do (No confirmation box)
            $(this).closest('.image-preview-box').fadeOut(300, function(){ $(this).remove(); });
        });

        // --- 6. Dynamic Specifications Logic (Tumhara Purana Code - KEEP) ---
        $('#add_spec_btn').click(function() {
            $('#specifications_wrapper').append(`
                <div class="row spec-row mb-2">
                    <div class="col-md-5"><input type="text" class="form-control" name="spec_keys[]" placeholder="Key"></div>
                    <div class="col-md-5"><input type="text" class="form-control" name="spec_values[]" placeholder="Value"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-spec"><i class="fa fa-times"></i></button></div>
                </div>
            `);
        });
        
        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.spec-row').remove();
        });

    });
</script>
@endpush

{{-- @push('scripts')
<script>
    $(document).ready(function() {
        $('#title').on('keyup', function() {
            var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');   
            $('#slug').val(slug);
        });

        $('#thumbnail_input').on('change', function(e) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#thumbnail_preview').html(`<img src="${e.target.result}" style="width: 100px; border-radius: 5px;">`);
            }
            if(e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
        });

        $('#add_spec_btn').click(function() {
            $('#specifications_wrapper').append(`
                <div class="row spec-row mb-2">
                    <div class="col-md-5"><input type="text" class="form-control" name="spec_keys[]" placeholder="Key"></div>
                    <div class="col-md-5"><input type="text" class="form-control" name="spec_values[]" placeholder="Value"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-spec"><i class="fa fa-times"></i></button></div>
                </div>
            `);
        });
        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.spec-row').remove();
        });

        $(document).on('click', '.remove-gallery-btn', function() {
            if(!confirm('Delete this gallery image permanently?')) return;
            
            let btn = $(this);
            let imageId = btn.data('id');
            btn.html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '/admin/product-images/' + imageId + '/delete',
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if(response.success) {
                        Toast.fire({ icon: 'success', title: response.message });
                        btn.closest('.image-card').fadeOut(300, function() { $(this).remove(); });
                    }
                }
            });
        });
    });
</script>
@endpush --}}