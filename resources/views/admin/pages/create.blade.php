@extends('admin.layouts.master')

@push('styles')
    <!-- Summernote Lite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="page-header">
    <h4 class="page-title">Create Page</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a>
        </li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Create</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Add New Page</div>
            </div>
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Enter page title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required placeholder="page-url-slug">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="page_type">Page Type <span class="text-danger">*</span></label>
                                <select class="form-select form-control" id="page_type" name="page_type" required>
                                    <option value="" disabled selected>Select Page Type</option>
                                    <option value="home" {{ old('page_type') == 'home' ? 'selected' : '' }}>Home Page (Section Based)</option>
                                    <option value="about" {{ old('page_type') == 'about' ? 'selected' : '' }}>About Us</option>
                                    <option value="contact" {{ old('page_type') == 'contact' ? 'selected' : '' }}>Contact Us</option>
                                    <option value="certificates" {{ old('page_type') == 'certificates' ? 'selected' : '' }}>Certificates</option>
                                    <option value="custom" {{ old('page_type') == 'custom' ? 'selected' : '' }}>Custom Page</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="content">Page Content</label>
                                <textarea class="form-control summernote-editor" id="summernote" name="content">{{ old('content') }}</textarea>
                                <small class="form-text text-muted">Leave blank for Home page as it uses dynamic sections.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meta_title">Meta Title (SEO)</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" placeholder="SEO Title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meta_description">Meta Description (SEO)</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="2" placeholder="SEO Description">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Save Page</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- @push('scripts')
    <!-- Summernote Lite JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // Summernote initialize
            $('#summernote').summernote({
                placeholder: 'Write page content here...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Auto-generate slug from title
            $('#title').on('keyup', function() {
                var title = $(this).val();
                var slug = title.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric with hyphen
                                .replace(/(^-|-$)+/g, '');   // Remove leading/trailing hyphens
                $('#slug').val(slug);
            });
        });
    </script>
@endpush --}}