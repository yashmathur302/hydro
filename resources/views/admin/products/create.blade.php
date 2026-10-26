@extends('admin.layouts.master')
@section('content')
<div class="page-header">
    <h4 class="page-title">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">{{ isset($product) ? 'Edit' : 'Create' }}</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <!-- Basic details (Name, slug, category, SKU, short desc, full desc) -->
                    <!-- Purana create HTML yahan rahega... (Skipping repetitive basic fields for brevity, unhe as it is rakhna) -->
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Product Name *</label><input type="text" class="form-control" name="name" id="title" required></div>
                        <div class="col-md-6 form-group"><label>URL Slug *</label><input type="text" class="form-control" name="slug" id="slug" required></div>
                        <div class="col-md-6 form-group">
                            <label>Category</label>
                            <select class="form-select form-control" name="category_id">
                                <option value="">Select Category</option>
                                @if(isset($categories)) @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach @endif
                            </select>
                        </div>
                        <div class="col-md-6 form-group"><label>SKU</label><input type="text" class="form-control" name="sku"></div>
                    </div>
                    <div class="form-group mt-2"><label>Short Description</label><textarea class="form-control" name="short_description" rows="3"></textarea></div>
                    <div class="form-group mt-2"><label>Full Description</label><textarea class="form-control summernote-editor" name="description"></textarea></div>
                    
                    <!-- Multiple Gallery Upload -->
                    

                    <!-- Specs -->
                    <div class="form-group mt-4 p-3" style="background: #f9fbfd; border: 1px dashed #ccc;">
                        <h5>Specifications</h5>
                        <div id="specifications_wrapper">
                            <div class="row spec-row mb-2">
                                <div class="col-md-5"><input type="text" class="form-control" name="spec_keys[]" placeholder="e.g., Weight"></div>
                                <div class="col-md-5"><input type="text" class="form-control" name="spec_values[]" placeholder="e.g., 5 kg"></div>
                                <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-spec"><i class="fa fa-times"></i></button></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add_spec_btn">Add More Specification</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group"><label>Status</label><select class="form-select form-control" name="status"><option value="published">Published</option><option value="draft">Draft</option></select></div>
                    <div class="form-group">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"><label class="form-check-label" for="is_featured">Show on Homepage</label></div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Thumbnail Image</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*" id="thumbnail_input">
                        <div id="thumbnail_preview" class="mt-2"></div>
                    </div>
                    <div class="form-group mt-2">
                        <label>Brochure (PDF)</label>
                        <input type="file" class="form-control" name="brochure_file" accept=".pdf,.doc,.docx">
                    </div>

                    <div class="form-group mt-2">
                        <h5>Product Image Gallery</h5>
                        <p class="text-muted small">Select multiple images to show in the product slider.</p>
                        <input type="file" class="form-control" name="gallery_images[]" accept="image/*" multiple id="gallery_upload">
                        <div id="gallery_preview" class="d-flex flex-wrap mt-2 gap-2"></div>
                    </div>
                    <hr>
                    <h5>SEO Settings</h5>
                    <div class="form-group"><label>Meta Title</label><input type="text" class="form-control" name="meta_title"></div>
                    <div class="form-group"><label>Meta Description</label><textarea class="form-control" name="meta_description" rows="2"></textarea></div>
                    <!-- New OG Image Field -->
                    <div class="form-group mt-2">
                        <label>OG Image (Social Share)</label>
                        <input type="file" class="form-control" name="og_image" accept="image/*" id="og_input">
                        <div id="og_preview" class="mt-2"></div>
                    </div>
                </div>
                <div class="card-action text-end">
                    <button type="submit" class="btn btn-success">Save Product</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#title').on('keyup', function() {
            var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');   
            $('#slug').val(slug);
        });

        // Single Image Preview
        $('#thumbnail_input').on('change', function(e) {
            if(e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) { $('#thumbnail_preview').html(`<img src="${e.target.result}" style="width: 100px; border-radius: 5px;">`); }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        $('#og_input').on('change', function(e) {
            if(e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) { $('#og_preview').html(`<img src="${e.target.result}" style="width: 100px; border-radius: 5px;">`); }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Live Multiple Gallery Preview
        $('#gallery_upload').on('change', function(e) {
            $('#gallery_preview').empty();
            let files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#gallery_preview').append(`<div style="display:inline-block; margin-right:5px;"><img src="${e.target.result}" style="height: 60px; width: 60px; object-fit: cover; border-radius: 5px;"></div>`);
                }
                reader.readAsDataURL(files[i]);
            }
        });

        $('#add_spec_btn').click(function() {
            $('#specifications_wrapper').append(`<div class="row spec-row mb-2"><div class="col-md-5"><input type="text" class="form-control" name="spec_keys[]" placeholder="Key"></div><div class="col-md-5"><input type="text" class="form-control" name="spec_values[]" placeholder="Value"></div><div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-spec"><i class="fa fa-times"></i></button></div></div>`);
        });
        $(document).on('click', '.remove-spec', function() { $(this).closest('.spec-row').remove(); });
    });
</script>
@endpush