@extends('admin.layouts.master')
@section('content')

<div class="page-header">
    <h4 class="page-title">Add Blog Post</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.blog_posts.index') }}">Blog Posts</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Add</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.blog_posts.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.blog_posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Main Data -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Post Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-md-6 form-group mt-2">
                            <label>URL Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug') }}" required>
                        </div>
                        <div class="col-md-6 form-group mt-2">
                            <label>Category</label>
                            <select class="form-select form-control" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label>Excerpt (Short Description)</label>
                        <textarea class="form-control" name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                    </div>

                    <div class="form-group mt-2">
                        <label>Full Content</label>
                        <textarea class="form-control summernote-editor" name="content">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings, Images & SEO -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-select form-control" name="status" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    
                    <hr>
                    
                    <!-- Featured Image -->
                    <div class="form-group">
                        <label>Featured Image</label>
                        <input type="file" class="form-control live-image-upload" name="featured_image" accept="image/*" data-preview="#featured_preview">
                        <div id="featured_preview" class="mt-2"></div>
                    </div>

                    <hr>
                    <h5>SEO Settings</h5>
                    <div class="form-group mt-2">
                        <label>Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}">
                    </div>
                    <div class="form-group mt-2">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                    </div>

                    <!-- OG Image -->
                    <div class="form-group mt-2">
                        <label>OG Image (Social Share)</label>
                        <input type="file" class="form-control live-image-upload" name="og_image" accept="image/*" data-preview="#og_preview">
                        <div id="og_preview" class="mt-2"></div>
                    </div>
                </div>
                <div class="card-action text-end">
                    <button type="submit" class="btn btn-success">Save Post</button>
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

        // Universal Live Preview
        $('.live-image-upload').on('change', function(e) {
            let targetDiv = $(this).data('preview');
            $(targetDiv).empty(); 
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
    });
</script>
@endpush