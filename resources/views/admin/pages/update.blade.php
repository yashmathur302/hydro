@extends('admin.layouts.master')

@push('styles')
    <!-- Summernote Lite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .section-item { border: 1px solid #ebedf2; border-radius: 5px; padding: 15px; margin-bottom: 10px; background: #fff; display: flex; justify-content: space-between; align-items: center; }
        .section-item .handle { cursor: grab; font-size: 1.2rem; color: #a1aab2; margin-right: 15px; }
        .builder-wrapper { background: #f9fbfd; padding: 20px; border-radius: 8px; border: 1px dashed #1572e8; }
    </style>
@endpush

@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Page: {{ $page->title }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Edit</a></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')
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

                    <!-- Basic Info -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slug">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="page_type">Page Type <span class="text-danger">*</span></label>
                                <select class="form-select form-control" id="page_type" name="page_type" required>
                                    <option value="home" {{ old('page_type', $page->page_type) == 'home' ? 'selected' : '' }}>Home Page (Section Based)</option>
                                    <option value="about" {{ old('page_type', $page->page_type) == 'about' ? 'selected' : '' }}>About Us</option>
                                    <option value="contact" {{ old('page_type', $page->page_type) == 'contact' ? 'selected' : '' }}>Contact Us</option>
                                    <option value="certificates" {{ old('page_type', $page->page_type) == 'certificates' ? 'selected' : '' }}>Certificates</option>
                                    <option value="custom" {{ old('page_type', $page->page_type) == 'custom' ? 'selected' : '' }}>Custom Page</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- HYBRID LOGIC WRAPPERS -->
                    
                    <!-- 1. Summernote Wrapper (Hidden if type is home) -->
                    <div id="standard-editor-wrapper" style="display: {{ old('page_type', $page->page_type) == 'home' ? 'none' : 'block' }};">
                        <div class="form-group">
                            <label for="content">Page Content</label>
                            <textarea class="form-control" id="summernote" name="content">{{ old('content', $page->content) }}</textarea>
                        </div>
                    </div>

                    <!-- 2. Section Builder Wrapper (Hidden if type is NOT home) -->
                    <div id="section-builder-wrapper" class="mt-3" style="display: {{ old('page_type', $page->page_type) == 'home' ? 'block' : 'none' }};">
                        <div class="builder-wrapper">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-cubes me-2"></i> Home Page Sections</h5>
                                <!-- Ye button hum next step me modal ke sath link karenge -->
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                    <i class="fa fa-plus"></i> Add New Section
                                </button>
                            </div>
                            
                            <!-- Sortable Section List -->
                            <div id="sortable-sections-list">
                                @forelse($page->sections as $section)
                                    <div class="section-item" data-id="{{ $section->id }}">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-grip-vertical handle"></i>
                                            <div>
                                                <strong>{{ strtoupper($section->section_type) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $section->title ?? 'No title provided' }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge {{ $section->status == 'active' ? 'badge-success' : 'badge-danger' }} me-2">
                                                {{ ucfirst($section->status) }}
                                            </span>
                                            <button type="button" class="btn btn-primary btn-sm edit-section-btn" data-id="{{ $section->id }}">Edit</button>
                                            <form action="{{ route('admin.page-sections.destroy', $section->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this section?')">Remove</button>
                                            </form>
                                            {{-- <button type="button" class="btn btn-danger btn-sm delete-section-btn" data-id="{{ $section->id }}">Remove</button> --}}
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-warning text-center">No sections added yet. Click "Add New Section" to start building your home page.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- SEO Info -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meta_title">Meta Title (SEO)</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meta_description">Meta Description (SEO)</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Update Page</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">Add New Home Page Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.page-sections.store') }}" method="POST">
                @csrf
                <input type="hidden" name="page_id" value="{{ $page->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="section_type">Select Section Type <span class="text-danger">*</span></label>
                        <select class="form-select form-control" id="section_type" name="section_type" required>
                            <option value="" disabled selected>-- Select Type --</option>
                            <option value="banner">Banner (Slider)</option>
                            <option value="about">About Us (Text & Image)</option>
                            <option value="industries">Industries We Serve</option>
                            <option value="products">Products Grid</option>
                            <option value="clients">Clients / Brands Slider</option>
                            <option value="blogs">Latest Blogs</option>
                            <option value="cta">Call To Action (CTA)</option>
                        </select>
                        <small class="form-text text-muted">Choose which module you want to add to the home page.</small>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label for="section_title">Section Title (Optional)</label>
                        <input type="text" class="form-control" id="section_title" name="title" placeholder="e.g., Our Latest Products">
                        <small class="form-text text-muted">This will be used as the main heading for the section.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <!-- Summernote Lite JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // 1. Initialize Summernote
            $('#summernote').summernote({
                placeholder: 'Write page content here...',
                tabsize: 2,
                height: 400,
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

            // 2. Auto-generate slug (Only if admin manually changes title, otherwise keep existing)
            $('#title').on('keyup', function() {
                var title = $(this).val();
                var slug = title.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-') 
                                .replace(/(^-|-$)+/g, '');   
                $('#slug').val(slug);
            });

            // 3. Real-time UI Switching Logic
            $('#page_type').on('change', function() {
                var selectedType = $(this).val();
                if(selectedType === 'home') {
                    $('#standard-editor-wrapper').slideUp();
                    $('#section-builder-wrapper').slideDown();
                } else {
                    $('#section-builder-wrapper').slideUp();
                    $('#standard-editor-wrapper').slideDown();
                }
            });
        });
    </script>
@endpush