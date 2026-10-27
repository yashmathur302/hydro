@extends('admin.layouts.master')

@section('content')
<div class="page-header">
    <h4 class="page-title">Categories Management</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Product Categories</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">All Categories</h4>
        <a href="{{ route('admin.product_categories.create') }}" class="btn btn-primary btn-round ms-auto">
            <i class="fa fa-plus"></i> Add New Category
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>PARENT CATEGORY</th>
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
                        <td>
                            @if($item->parent)
                                <span class="badge badge-count">{{ $item->parent->name }}</span>
                            @else
                                <span class="text-muted">NA</span>
                            @endif
                        </td>
                        <td>{{ $item->slug }}</td>
                        <td>
                            <span class="badge {{ $item->status == 'active' ? 'badge-primary' : 'badge-secondary' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.product_categories.edit', $item->id) }}" class="btn btn-link btn-primary px-2 py-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.product_categories.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-link btn-danger px-2 py-1 global-delete-btn" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="card-footer">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection