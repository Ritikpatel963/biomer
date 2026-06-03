@extends('layout.layout')
@section('title', 'Blog Reviews')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h4 class="fw-semibold mb-1">Blog Reviews</h4>
            <p class="text-secondary-light mb-0">Review incoming blog comments and moderate customer feedback from the blog section.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog Reviews</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-24">
        @foreach([
            ['label' => 'Total reviews', 'value' => $counts['all'], 'color' => 'primary', 'icon' => 'ri-message-3-line'],
            ['label' => 'Pending approval', 'value' => $counts['pending'], 'color' => 'warning', 'icon' => 'ri-time-line'],
            ['label' => 'Approved', 'value' => $counts['approved'], 'color' => 'success', 'icon' => 'ri-checkbox-circle-line'],
            ['label' => 'Rejected', 'value' => $counts['rejected'], 'color' => 'danger', 'icon' => 'ri-close-circle-line'],
        ] as $stat)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-{{ $stat['color'] }}-focus text-{{ $stat['color'] }}-main fs-24" style="width:52px;height:52px;">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-secondary-light mb-1">{{ $stat['label'] }}</p>
                            <h5 class="fw-semibold mb-0">{{ $stat['value'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom bg-base">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
                        <a href="{{ route('dashboard.blog-reviews.index', ['status' => $key, 'q' => $search ?? '']) }}"
                           class="btn btn-sm {{ $status === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $label }}
                            <span class="badge bg-white text-primary-600 ms-1">{{ $counts[$key] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('dashboard.blog-reviews.index') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-neutral-100 border-end-0"><i class="ri-search-line"></i></span>
                            <input type="search" name="q" class="form-control border-start-0" placeholder="Search by author, blog, or comment" value="{{ old('q', $search ?? '') }}">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-data-table" data-page-length="10" data-no-sort-targets="7">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Reviewer</th>
                            <th>Blog</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td class="ps-3 text-secondary-light">{{ $review->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-secondary-focus text-secondary-main text-center" style="width:40px;height:40px;line-height:40px;font-size:0.95rem;">
                                            {{ strtoupper(substr($review->name ?: ($review->customer->name ?? 'U'), 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $review->name ?: ($review->customer->name ?? 'Guest') }}</div>
                                            <small class="text-secondary-light">{{ $review->email ?: ($review->customer->email ?? '—') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($review->blog)
                                        <a href="{{ route('frontend.blog.show', $review->blog->slug) }}" class="text-primary-600 fw-medium text-decoration-none" target="_blank">
                                            {{ Str::limit($review->blog->title, 45) }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ri-star-{{ $i <= $review->rating ? 'fill' : 'line' }} text-warning-main fs-14"></i>
                                        @endfor
                                        <span class="text-secondary-light ms-1">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                <td style="min-width: 280px; max-width: 320px; white-space: normal;">
                                    <p class="mb-1 text-truncate" style="max-width: 320px;">{{ Str::limit($review->comment, 120) }}</p>
                                </td>
                                <td>
                                    @if($review->status === 'approved')
                                        <span class="badge bg-success-focus text-success-main">Approved</span>
                                    @elseif($review->status === 'rejected')
                                        <span class="badge bg-danger-focus text-danger-main">Rejected</span>
                                    @else
                                        <span class="badge bg-warning-focus text-warning-main">Pending</span>
                                    @endif
                                </td>
                                <td class="text-secondary-light">{{ $review->created_at->format('d M Y') }}</td>
                                <td class="pe-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($review->status !== 'approved')
                                            <form action="{{ route('dashboard.blog-reviews.approve', $review) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success-focus text-success-main px-10 py-3" title="Approve">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($review->status !== 'rejected')
                                            <form action="{{ route('dashboard.blog-reviews.reject', $review) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning-focus text-warning-main px-10 py-3" title="Reject">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('dashboard.blog-reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger-focus text-danger-main px-10 py-3" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-secondary-light">
                                    No blog reviews found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="px-16 py-12 border-top">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
