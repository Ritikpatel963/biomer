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
                <h2 class="admin-page-card__title">Blog Management</h2>
                <p class="admin-page-card__desc">All blog data now sits inside the same card pattern as the settings pages, so the layout stays consistent while the content changes.</p>
            </div>
            <div class="admin-page-card__actions">
                <a href="{{ route('addBlog') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="ri-add-line"></i> Add New Post
                </a>
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
                <span class="text-muted small">{{ $blogs->count() }} posts</span>
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
                            @foreach ($blogs as $blog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $blog->thumbnail_url }}"
                                            alt=""
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
                                            <a href="{{ route('editBlog', $blog->id) }}"
                                                class="btn btn-sm btn-primary-100 text-primary-600 px-12 py-6 radius-8"
                                                title="Edit">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            <form action="{{ route('destroyBlog', $blog->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this post? This cannot be undone.')">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger-100 text-danger-600 px-12 py-6 radius-8"
                                                    title="Delete">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $('#blogsTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [1, 7] }
        ],
        language: {
            search: 'Search posts:',
            lengthMenu: 'Show _MENU_ posts',
        }
    });
</script>
@endpush
