@extends('admin.layouts.master')

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
                                <textarea class="form-control summernote-editor" id="summernote" name="content">{{ old('content', $page->content) }}</textarea>
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
                                                {{-- <form action="{{ route('admin.page-sections.destroy', $section->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this section?')">Remove</button>
                                                </form> --}}
                                                <button type="button" class="btn btn-danger btn-sm delete-section-btn" data-id="{{ $section->id }}">Remove</button>
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

    <!-- Edit Section Modal -->
    <div class="modal-body">
        <div class="form-group">
            <label>Section Title</label>
            <input type="text" class="form-control" id="edit_section_title" name="title">
        </div>
        <div class="form-group mt-2">
            <label>Section Subtitle</label>
            <input type="text" class="form-control" id="edit_section_subtitle" name="subtitle">
        </div>

        <!-- Content Field (Now with Summernote) -->
        <div class="form-group mt-2 dynamic-field field-content d-none">
            <label>Content (Text)</label>
            <textarea class="form-control modal-summernote" id="edit_section_content" name="content"></textarea>
        </div>

        <div class="form-group mt-2 dynamic-field field-image d-none">
            <label>Upload Image</label>
            <input type="file" class="form-control" id="edit_section_image" name="image" accept="image/*">
            <small class="text-muted mt-1" id="current_image_text"></small>
        </div>

        <!-- Products Multi-Select -->
        <div class="form-group mt-2 dynamic-field field-products d-none">
            <label>Select Products to Display</label>
            <select class="form-select select2-modal" id="edit_section_products" name="settings_product_ids[]"
                multiple="multiple" style="width: 100%;">
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Clients Multi-Select -->
        <div class="form-group mt-2 dynamic-field field-clients d-none">
            <label>Select Clients/Brands</label>
            <select class="form-select select2-modal" id="edit_section_clients" name="settings_client_ids[]"
                multiple="multiple" style="width: 100%;">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- CTA Buttons -->
        <div class="row mt-2 dynamic-field field-button d-none">
            <div class="col-md-6 form-group">
                <label>Button Text</label>
                <input type="text" class="form-control" id="edit_section_button_text" name="button_text">
            </div>
            <div class="col-md-6 form-group">
                <label>Button Link</label>
                <input type="text" class="form-control" id="edit_section_button_link" name="button_link">
            </div>
        </div>

        <div class="form-group mt-3">
            <label>Status</label>
            <select class="form-select form-control" id="edit_section_status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // 0. Initialize Select2 and Summernote inside Modal
            $('#editSectionModal').on('shown.bs.modal', function () {
                $('.select2-modal').select2({
                    dropdownParent: $('#editSectionModal'),
                    width: '100%'
                });
                
                $('.modal-summernote').summernote({
                    tabsize: 2, 
                    height: 200,
                    dialogsInBody: true, // Z-index issue fix for modals
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol']],
                        ['view', ['codeview']]
                    ]
                });
            });
            
            // 1. Auto-generate slug from title
            $('#title').on('keyup', function() {
                var title = $(this).val();
                var slug = title.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-') 
                                .replace(/(^-|-$)+/g, '');   
                $('#slug').val(slug);
            });

            // 2. Real-time UI Switching Logic based on Page Type
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

            // 3. jQuery UI Sortable for Sections
            $('#sortable-sections-list').sortable({
                handle: '.handle',
                update: function(event, ui) {
                    let orderData = [];
                    $('.section-item').each(function(index) {
                        orderData.push({
                            id: $(this).data('id'),
                            sort_order: index + 1
                        });
                    });

                    $.ajax({
                        url: "{{ route('admin.page-sections.updateOrder') }}",
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            orders: orderData
                        },
                        success: function(response) {
                            if(response.success) {
                                Toast.fire({ icon: 'success', title: response.message });
                            }
                        },
                        error: function(xhr) {
                            Toast.fire({ icon: 'error', title: 'Failed to update sorting order.' });
                        }
                    });
                }
            });

            // 4. Open Edit Section Modal (AJAX Fetch)
            $(document).on('click', '.edit-section-btn', function() {
                let sectionId = $(this).data('id');
                let btn = $(this);
                let originalText = btn.text();
                
                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: '/admin/page-sections/' + sectionId,
                    type: 'GET',
                    success: function(response) {
                        btn.prop('disabled', false).text(originalText);
                        if(response.success) {
                            let data = response.data;
                            
                            // Reset form and hide all dynamic fields first
                            $('#editSectionForm')[0].reset();
                            $('.dynamic-field').addClass('d-none');
                            $('#current_image_text').text('');
                            
                            // Reset Select2 and Summernote content
                            $('#edit_section_products').val(null).trigger('change');
                            $('#edit_section_clients').val(null).trigger('change');
                            if ($('.modal-summernote').hasClass('summernote-initialized')) {
                                $('.modal-summernote').summernote('code', '');
                            }

                            // Populate basic data
                            $('#edit_section_id').val(data.id);
                            $('#edit_section_title').val(data.title);
                            $('#edit_section_subtitle').val(data.subtitle);
                            $('#edit_section_status').val(data.status);
                            
                            // Logic to show/hide fields based on section_type
                            let type = data.section_type;
                            
                            if(['about', 'cta'].includes(type)) {
                                $('.field-content, .field-button').removeClass('d-none');
                                $('.modal-summernote').summernote('code', data.content);
                                $('#edit_section_button_text').val(data.button_text);
                                $('#edit_section_button_link').val(data.button_link);
                            }
                            
                            if(['banner', 'about'].includes(type)) {
                                $('.field-image').removeClass('d-none');
                                if(data.image) {
                                    $('#current_image_text').html('Current: <a href="/'+data.image+'" target="_blank">View Image</a>');
                                }
                            }

                            // Dynamic Dropdown population for Select2
                            if(type === 'products') {
                                $('.field-products').removeClass('d-none');
                                if(data.settings && data.settings.product_ids) {
                                    $('#edit_section_products').val(data.settings.product_ids).trigger('change');
                                }
                            }

                            if(type === 'clients') {
                                $('.field-clients').removeClass('d-none');
                                if(data.settings && data.settings.client_ids) {
                                    $('#edit_section_clients').val(data.settings.client_ids).trigger('change');
                                }
                            }

                            $('#editSectionModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text(originalText);
                        Toast.fire({ icon: 'error', title: 'Failed to fetch section data.' });
                    }
                });
            });

            // 5. Submit Edit Section Form (AJAX Update with File Upload)
            $('#editSectionForm').on('submit', function(e) {
                e.preventDefault();
                let sectionId = $('#edit_section_id').val();
                let saveBtn = $('#saveEditBtn');
                
                // Use FormData to handle file uploads via AJAX
                let formData = new FormData(this);
                formData.append('_method', 'PUT'); // Laravel spoofing for PUT
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                saveBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: '/admin/page-sections/' + sectionId,
                    type: 'POST', // Has to be POST with _method=PUT for FormData
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        saveBtn.prop('disabled', false).text('Save Changes');
                        if(response.success) {
                            $('#editSectionModal').modal('hide');
                            Toast.fire({ icon: 'success', title: response.message });
                            
                            // Update UI instantly
                            let updatedTitle = $('#edit_section_title').val();
                            $(`.section-item[data-id="${sectionId}"] small.text-muted`).text(updatedTitle ? updatedTitle : 'No title provided');
                            
                            let statusText = $('#edit_section_status').val();
                            let badgeClass = statusText === 'active' ? 'badge-success' : 'badge-danger';
                            $(`.section-item[data-id="${sectionId}"] .badge`).removeClass('badge-success badge-danger').addClass(badgeClass).text(statusText.charAt(0).toUpperCase() + statusText.slice(1));
                        }
                    },
                    error: function(xhr) {
                        saveBtn.prop('disabled', false).text('Save Changes');
                        let errorMsg = 'Something went wrong!';
                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMsg = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                            $.each(errors, function(key, value) {
                                errorMsg += '<li>' + value[0] + '</li>';
                            });
                            errorMsg += '</ul>';
                        }
                        Toast.fire({ icon: 'error', title: 'Validation Failed', html: errorMsg });
                    }
                });
            });

            // 6. Delete Section (AJAX)
            $(document).on('click', '.delete-section-btn', function() {
                // Use SweetAlert for confirmation instead of default confirm()
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove this section?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let button = $(this);
                        let sectionId = button.data('id');
                        let sectionItem = button.closest('.section-item');

                        button.prop('disabled', true).text('Removing...');

                        $.ajax({
                            url: '/admin/page-sections/' + sectionId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if(response.success) {
                                    Toast.fire({ icon: 'success', title: response.message });
                                    
                                    sectionItem.fadeOut(300, function() {
                                        $(this).remove();
                                        if($('.section-item').length === 0) {
                                            $('#sortable-sections-list').html('<div class="alert alert-warning text-center">No sections added yet. Click "Add New Section" to start building your home page.</div>');
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                button.prop('disabled', false).text('Remove');
                                Toast.fire({ icon: 'error', title: 'Something went wrong while deleting!' });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush

{{-- @push('scripts')
    <script>
        $(document).ready(function() {
            
            // 1. Auto-generate slug from title
            $('#title').on('keyup', function() {
                var title = $(this).val();
                var slug = title.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-') 
                                .replace(/(^-|-$)+/g, '');   
                $('#slug').val(slug);
            });

            // 2. Real-time UI Switching Logic based on Page Type
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

            // 3. jQuery UI Sortable for Sections
            $('#sortable-sections-list').sortable({
                handle: '.handle',
                update: function(event, ui) {
                    let orderData = [];
                    $('.section-item').each(function(index) {
                        orderData.push({
                            id: $(this).data('id'),
                            sort_order: index + 1
                        });
                    });

                    $.ajax({
                        url: "{{ route('admin.page-sections.updateOrder') }}",
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            orders: orderData
                        },
                        success: function(response) {
                            if(response.success) {
                                Toast.fire({ icon: 'success', title: response.message });
                            }
                        },
                        error: function(xhr) {
                            Toast.fire({ icon: 'error', title: 'Failed to update sorting order.' });
                        }
                    });
                }
            });

            // 4. Open Edit Section Modal (AJAX Fetch)
            $(document).on('click', '.edit-section-btn', function() {
                let sectionId = $(this).data('id');
                let btn = $(this);
                let originalText = btn.text();
                
                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: '/admin/page-sections/' + sectionId,
                    type: 'GET',
                    success: function(response) {
                        btn.prop('disabled', false).text(originalText);
                        if(response.success) {
                            let data = response.data;
                            
                            // Reset form and hide all dynamic fields first
                            $('#editSectionForm')[0].reset();
                            $('.dynamic-field').addClass('d-none');
                            $('#current_image_text').text('');

                            // Populate basic data
                            $('#edit_section_id').val(data.id);
                            $('#edit_section_title').val(data.title);
                            $('#edit_section_subtitle').val(data.subtitle);
                            $('#edit_section_status').val(data.status);
                            
                            // Logic to show/hide fields based on section_type
                            let type = data.section_type;
                            
                            if(['about', 'cta'].includes(type)) {
                                $('.field-content, .field-button').removeClass('d-none');
                                $('#edit_section_content').val(data.content);
                                $('#edit_section_button_text').val(data.button_text);
                                $('#edit_section_button_link').val(data.button_link);
                            }
                            
                            if(['banner', 'about'].includes(type)) {
                                $('.field-image').removeClass('d-none');
                                if(data.image) {
                                    $('#current_image_text').html('Current: <a href="/'+data.image+'" target="_blank">View Image</a>');
                                }
                            }

                            if(['products', 'blogs', 'clients'].includes(type)) {
                                $('.field-limit').removeClass('d-none');
                                if(data.settings && data.settings.limit) {
                                    $('#edit_section_limit').val(data.settings.limit);
                                }
                            }

                            $('#editSectionModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text(originalText);
                        Toast.fire({ icon: 'error', title: 'Failed to fetch section data.' });
                    }
                });
            });

            // 5. Submit Edit Section Form (AJAX Update with File Upload)
            $('#editSectionForm').on('submit', function(e) {
                e.preventDefault();
                let sectionId = $('#edit_section_id').val();
                let saveBtn = $('#saveEditBtn');
                
                // Use FormData to handle file uploads via AJAX
                let formData = new FormData(this);
                formData.append('_method', 'PUT'); // Laravel spoofing for PUT
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                saveBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: '/admin/page-sections/' + sectionId,
                    type: 'POST', // Has to be POST with _method=PUT for FormData
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        saveBtn.prop('disabled', false).text('Save Changes');
                        if(response.success) {
                            $('#editSectionModal').modal('hide');
                            Toast.fire({ icon: 'success', title: response.message });
                            
                            // Update UI instantly
                            let updatedTitle = $('#edit_section_title').val();
                            $(`.section-item[data-id="${sectionId}"] small.text-muted`).text(updatedTitle ? updatedTitle : 'No title provided');
                            
                            let statusText = $('#edit_section_status').val();
                            let badgeClass = statusText === 'active' ? 'badge-success' : 'badge-danger';
                            $(`.section-item[data-id="${sectionId}"] .badge`).removeClass('badge-success badge-danger').addClass(badgeClass).text(statusText.charAt(0).toUpperCase() + statusText.slice(1));
                        }
                    },
                    error: function(xhr) {
                        saveBtn.prop('disabled', false).text('Save Changes');
                        let errorMsg = 'Something went wrong!';
                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMsg = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                            $.each(errors, function(key, value) {
                                errorMsg += '<li>' + value[0] + '</li>';
                            });
                            errorMsg += '</ul>';
                        }
                        Toast.fire({ icon: 'error', title: 'Validation Failed', html: errorMsg });
                    }
                });
            });

            // 6. Delete Section (AJAX)
            $(document).on('click', '.delete-section-btn', function() {
                // Use SweetAlert for confirmation instead of default confirm()
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove this section?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let button = $(this);
                        let sectionId = button.data('id');
                        let sectionItem = button.closest('.section-item');

                        button.prop('disabled', true).text('Removing...');

                        $.ajax({
                            url: '/admin/page-sections/' + sectionId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if(response.success) {
                                    Toast.fire({ icon: 'success', title: response.message });
                                    
                                    sectionItem.fadeOut(300, function() {
                                        $(this).remove();
                                        if($('.section-item').length === 0) {
                                            $('#sortable-sections-list').html('<div class="alert alert-warning text-center">No sections added yet. Click "Add New Section" to start building your home page.</div>');
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                button.prop('disabled', false).text('Remove');
                                Toast.fire({ icon: 'error', title: 'Something went wrong while deleting!' });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush --}}