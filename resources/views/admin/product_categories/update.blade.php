@extends('admin.layouts.master')
@section('content')

<div class="page-header">
    <h4 class="page-title">{{ isset($product) ? 'Edit Product Category' : 'Add Product Category ' }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.product_categories.index') }}">Product Categories</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Edit</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.product_categories.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.product_categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Error display for parent_id conflict -->
    @if($errors->has('parent_id'))
        <div class="alert alert-danger">{{ $errors->first('parent_id') }}</div>
    @endif

    <div class="row">
        <!-- Main Data -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="title" value="{{ old('name', $category->name) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>URL Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label>Parent Category (Optional)</label>
                        <select class="form-select form-control" name="parent_id">
                            <option value="">-- No Parent (Main Category) --</option>
                            @foreach($parentCategories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select if this is a sub-category.</small>
                    </div>

                    <div class="form-group mt-2">
                        <label>Description</label>
                        <textarea class="form-control" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-select form-control" name="status" required>
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label>Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}">
                        <small class="text-muted">Higher number means it will appear lower in the list.</small>
                    </div>
                </div>
                <div class="card-action text-end">
                    <button type="submit" class="btn btn-success">Update Category</button>
                    <a href="{{ route('admin.product_categories.index') }}" class="btn btn-danger">Cancel</a>
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
    });
</script>
@endpush