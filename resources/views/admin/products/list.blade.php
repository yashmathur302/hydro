@extends('admin.layouts.master')

@section('content')
<div class="page-header">
    <h4 class="page-title">Products Management</h4>
    
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Products</a></li>
    </ul>
</div>

<div class="card">
    <!-- Card header bilkul Pages module jaisa -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">All Products</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-round ms-auto">
            <i class="fa fa-plus"></i> Add New Product
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>THUMBNAIL</th>
                        <th>NAME</th>
                        <th>CATEGORY</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            @if($item->thumbnail)
                                <img src="{{ asset($item->thumbnail) }}" width="40" style="border-radius: 5px;">
                            @else
                                <span class="badge badge-secondary">No Image</span>
                            @endif
                        </td>
                        <td>{{ $item->name }}<br><small class="text-muted">SKU: {{ $item->sku }}</small></td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $item->status == 'published' ? 'badge-primary' : 'badge-secondary' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td>
                            <!-- Icon buttons just like screenshot -->
                            <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn-link btn-primary px-2 py-1">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-link btn-danger px-2 py-1 global-delete-btn">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
    <div class="card-footer">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection