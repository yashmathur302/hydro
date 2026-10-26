@extends('admin.layouts.master')
@section('content')

<div class="page-header">
    <h4 class="page-title">Edit Client: {{ $client->name }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.clients.index') }}">Client</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Edit</a></li>
    </ul>
    <div class="ms-auto">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-round btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Deferred Removal Tracking -->
    <div id="removed_images_container"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row">
                        <!-- Client Name (6 Col) -->
                        <div class="col-md-6 form-group">
                            <label>Client / Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $client->name) }}" required>
                        </div>
                        
                        <!-- Status (6 Col) -->
                        <div class="col-md-6 form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-select form-control" name="status" required>
                                <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <!-- Logo Upload & Preview (12 Col) -->
                        <div class="col-md-12 form-group">
                            <label>Client Logo</label>
                            <input type="file" class="form-control live-image-upload" name="logo" accept="image/*" data-preview="#logo_preview">
                            <div id="logo_preview" class="mt-2">
                                @if($client->logo)
                                <div class="position-relative d-inline-block image-preview-box mt-2">
                                    <img src="{{ asset($client->logo) }}" style="width: 120px; height: 120px; object-fit: contain; background: #f8f9fa; border-radius: 5px; border: 1px solid #ccc; padding: 5px;">
                                    <div class="mt-1 d-flex justify-content-between">
                                        <a href="{{ asset($client->logo) }}" target="_blank" class="btn btn-info btn-sm py-0 px-2" title="View"><i class="fa fa-eye"></i></a>
                                        <button type="button" class="btn btn-danger btn-sm py-0 px-2 remove-single-image-btn" data-key="logo" title="Remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Buttons Bottom Right -->
                <div class="card-action text-end">
                    <button type="submit" class="btn btn-success">Update Client</button>
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
        // Universal Live Preview
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

        // Deferred Deletion Script (No confirmation, hide UI and append hidden input)
        $(document).on('click', '.remove-single-image-btn', function() {
            let key = $(this).data('key'); // 'logo' aayega yaha
            
            // Append hidden input for backend tracking
            $('#removed_images_container').append(`<input type="hidden" name="remove_images[]" value="${key}">`);
            
            // Clear input
            $(`input[name="${key}"]`).val('');
            
            // Fade out UI
            $(this).closest('.image-preview-box').fadeOut(300, function(){ $(this).remove(); });
        });
    });
</script>
@endpush