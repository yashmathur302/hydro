@extends('admin.layouts.master')

@section('content')
<div class="page-header">
    <h4 class="page-title">Blog Categories</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.blog_categories.index') }}">Blogs</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Categories</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">All Categories</h4>
        <button type="button" class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa fa-plus"></i> Add New Category
        </button>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>SLUG</th>
                        <th>STATUS</th>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->slug }}</td>
                        <td>
                            <span class="badge {{ $item->status == 'active' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-link btn-primary px-2 py-1 edit-category-btn" data-id="{{ $item->id }}" data-bs-toggle="tooltip" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.blog_categories.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-link btn-danger px-2 py-1 global-delete-btn" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($categories->hasPages())
    <div class="card-footer">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- ================= ADD MODAL ================= -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_name" name="name" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>URL Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_slug" name="slug" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-select form-control" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveAddBtn">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT MODAL ================= -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm">
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>URL Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_slug" name="slug" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-select form-control" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="saveEditBtn">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
        // Setup CSRF Token for AJAX globally
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto Slug (Add Form)
        $('#add_name').on('keyup', function() {
            var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');   
            $('#add_slug').val(slug);
        });

        // Auto Slug (Edit Form)
        $('#edit_name').on('keyup', function() {
            var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');   
            $('#edit_slug').val(slug);
        });

        // Submit Add Form (AJAX)
        $('#addCategoryForm').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#saveAddBtn');
            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('admin.blog_categories.store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        $('#addCategoryModal').modal('hide');
                        Toast.fire({ icon: 'success', title: response.message });
                        setTimeout(() => { location.reload(); }, 1000); // Reload to update list
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Save Category');
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '<ul style="text-align: left; padding-left: 20px;">';
                        $.each(errors, function(key, value) { errorMsg += '<li>' + value[0] + '</li>'; });
                        errorMsg += '</ul>';
                        Toast.fire({ icon: 'error', title: 'Validation Failed', html: errorMsg });
                    } else {
                        Toast.fire({ icon: 'error', title: 'Something went wrong!' });
                    }
                }
            });
        });

        // Open Edit Modal & Fetch Data (AJAX)
        $('.edit-category-btn').on('click', function() {
            let id = $(this).data('id');
            let btn = $(this);
            
            btn.html('<i class="fa fa-spinner fa-spin"></i>'); // Loading icon

            $.ajax({
                url: '/admin/blog_categories/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    btn.html('<i class="fa fa-edit"></i>'); // Reset icon
                    if(response.success) {
                        $('#edit_id').val(response.data.id);
                        $('#edit_name').val(response.data.name);
                        $('#edit_slug').val(response.data.slug);
                        $('#edit_status').val(response.data.status);
                        
                        $('#editCategoryModal').modal('show');
                    }
                },
                error: function() {
                    btn.html('<i class="fa fa-edit"></i>');
                    Toast.fire({ icon: 'error', title: 'Failed to fetch category data.' });
                }
            });
        });

        // Submit Edit Form (AJAX)
        $('#editCategoryForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let btn = $('#saveEditBtn');
            btn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: '/admin/blog_categories/' + id,
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        $('#editCategoryModal').modal('hide');
                        Toast.fire({ icon: 'success', title: response.message });
                        setTimeout(() => { location.reload(); }, 1000); // Reload to update list
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Update Category');
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '<ul style="text-align: left; padding-left: 20px;">';
                        $.each(errors, function(key, value) { errorMsg += '<li>' + value[0] + '</li>'; });
                        errorMsg += '</ul>';
                        Toast.fire({ icon: 'error', title: 'Validation Failed', html: errorMsg });
                    } else {
                        Toast.fire({ icon: 'error', title: 'Something went wrong!' });
                    }
                }
            });
        });

    });
</script>
@endpush