@extends('admin.layouts.master')

@section('content')
<div class="page-header">
    <h4 class="page-title">Clients & Brands</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Clients</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">All Clients</h4>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-round ms-auto">
            <i class="fa fa-plus"></i> Add New Client
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>LOGO</th>
                        <th>NAME</th>
                        <th>STATUS</th>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            @if($item->logo)
                                <img src="{{ asset($item->logo) }}" width="60" style="object-fit: contain; background: #f8f9fa; padding: 5px; border-radius: 5px; border: 1px solid #ebedf2;">
                            @else
                                <span class="badge badge-secondary">No Logo</span>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="badge {{ $item->status == 'active' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.clients.edit', $item->id) }}" class="btn btn-link btn-primary px-2 py-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.clients.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-link btn-danger px-2 py-1 global-delete-btn" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No clients found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($clients->hasPages())
    <div class="card-footer">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection