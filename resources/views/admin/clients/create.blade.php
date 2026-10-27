@extends('admin.layouts.master')
@section('content')

<div class="page-header">
    <h4 class="page-title">Add Client</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.clients.index') }}">Client</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Add</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row">
                        <!-- Client Name (6 Col) -->
                        <div class="col-md-6 form-group">
                            <label>Client / Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <!-- Status (6 Col) -->
                        <div class="col-md-6 form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-select form-control" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <!-- Logo Upload (12 Col) -->
                        <div class="col-md-12 form-group">
                            <label>Client Logo</label>
                            <input type="file" class="form-control live-image-upload" name="logo" accept="image/*" data-preview="#logo_preview">
                            <div id="logo_preview" class="mt-2"></div>
                        </div>
                    </div>

                </div>
                <!-- Buttons Bottom Right -->
                <div class="card-action text-end">
                    <button type="submit" class="btn btn-success">Save Client</button>
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Universal Live Preview for Image
        $('.live-image-upload').on('change', function(e) {
            let targetDiv = $(this).data('preview');
            $(targetDiv).empty(); 
            if(e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $(targetDiv).html(`
                        <div class="position-relative d-inline-block image-preview-box mt-2">
                            <img src="${e.target.result}" style="width: 120px; height: 120px; object-fit: contain; background: #f8f9fa; border-radius: 5px; border: 2px solid #28a745; padding: 5px;">
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