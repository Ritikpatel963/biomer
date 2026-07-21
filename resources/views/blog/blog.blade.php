@extends('layout.layout')

@php
    $title = 'Blog Posts';
    $subTitle = 'Blog Posts';
@endphp

@section('content')

<div class="admin-shell">
    <div class="admin-page-card">
        <div class="admin-page-card__header">
            <div>
                <span class="admin-page-card__eyebrow">Content Management</span>
                
            </div>
            <div class="admin-page-card__actions">
                @can('create blogs')
                <a href="{{ route('addBlog') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="ri-add-line"></i> Add New Post
                </a>
                @endcan
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4 mb-0" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="admin-section-card mt-4">
            <div class="admin-section-card__header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h5 class="card-title mb-0">All Blog Posts</h5>
                <span class="text-muted small">{{ $blogs->total() }} posts</span>
            </div>
            <div class="admin-section-card__body">
                <div class="table-responsive">
                    <table class="table table-hover" id="blogsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs as $blog)
                                <tr>
                                    <td>{{ $blogs->firstItem() + $loop->index }}</td>
                                    <td>
                                        <img src="{{ $blog->thumbnail_url }}"
                                            alt="{{ $blog->thumbnail_alt_text }}"
                                            class="w-48-px h-48-px object-fit-cover radius-8">
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-neutral-800 text-line-2" style="max-width:200px;display:block;">
                                            {{ $blog->title }}
                                        </span>
                                    </td>
                                    <td>{{ $blog->category->name ?? '-' }}</td>
                                    <td>
                                        @if($blog->tags)
                                            @foreach(explode(',', $blog->tags) as $tag)
                                                <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4 text-xs">{{ trim($tag) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($blog->status === 'published')
                                            <span class="badge bg-success-100 text-success-700 px-8 py-4 radius-4 text-xs">Published</span>
                                        @else
                                            <span class="badge bg-warning-100 text-warning-700 px-10 py-5 radius-8">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $blog->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-8">
                                            <a href="{{ route('blogDetails', $blog->id) }}"
                                                class="btn btn-sm btn-neutral-100 text-neutral-600 px-12 py-6 radius-8"
                                                title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @can('edit blogs')
                                            <a href="{{ route('editBlog', $blog->id) }}"
                                                class="btn btn-sm btn-primary-100 text-primary-600 px-12 py-6 radius-8"
                                                title="Edit">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            @endcan
                                            @can('delete blogs')
                                            <form action="{{ route('destroyBlog', $blog->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this post? This cannot be undone.')">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger-100 text-danger-600 px-12 py-6 radius-8"
                                                    title="Delete">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No blog posts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($blogs->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
