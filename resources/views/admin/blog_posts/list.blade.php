@extends('admin.layouts.master')

@section('content')
<div class="page-header">
    <h4 class="page-title">Blog Posts</h4>
    <ul class="breadcrumbs">
        <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Blogs</a></li>
    </ul>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">All Posts</h4>
<<<<<<< HEAD
        <a href="{{ route('admin.blog_posts.create') }}" class="btn btn-primary btn-round btn-sm">
=======
        <a href="{{ route('admin.blog_posts.create') }}" class="btn btn-primary btn-round ms-auto">
>>>>>>> 06adde4e3eeec26c7c33e9693393cc10486864c7
            <i class="fa fa-plus"></i> Add New Post
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IMAGE</th>
                        <th>TITLE</th>
                        <th>CATEGORY</th>
                        <th>STATUS</th>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            @if($item->featured_image)
                                <img src="{{ asset($item->featured_image) }}" width="60" style="object-fit: cover; border-radius: 5px;">
                            @else
                                <span class="badge badge-secondary">No Image</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($item->title, 40) }}</td>
                        <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                        <td>
                            <span class="badge {{ $item->status == 'published' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.blog_posts.edit', $item->id) }}" class="btn btn-link btn-primary px-2 py-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.blog_posts.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-link btn-danger px-2 py-1 global-delete-btn" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No posts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($posts->hasPages())
    <div class="card-footer">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection