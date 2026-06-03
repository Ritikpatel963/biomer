@extends('layout.layout')
@section('title', 'Product Reviews')

@section('content')
<div class="dashboard-main-body">

    {{-- Page header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h4 class="fw-semibold mb-1">Product Reviews</h4>
            <p class="text-secondary-light mb-0">Manage product feedback, approve new reviews, and keep review quality high.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Product Reviews</li>
            </ol>
        </nav>
    </div>

    {{-- Success alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Stats cards --}}
    <div class="row g-3 mb-24">
        @foreach([
            ['label' => 'Total reviews', 'value' => $counts['all'], 'color' => 'primary', 'icon' => 'ri-star-line'],
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

    {{-- Reviews panel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom bg-base">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
                        <a href="{{ route('dashboard.reviews.index', ['status' => $key, 'q' => $search ?? '']) }}"
                           class="btn btn-sm {{ $status === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $label }}
                            <span class="badge bg-white text-primary-600 ms-1">{{ $counts[$key] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('dashboard.reviews.index') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-neutral-100 border-end-0"><i class="ri-search-line"></i></span>
                            <input type="search" name="q" class="form-control border-start-0" placeholder="Search by customer, product or review" value="{{ old('q', $search ?? '') }}">
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
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Review</th>
                            <th>Rating</th>
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
                                        {{ strtoupper(substr($review->customer->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $review->customer->name ?? 'Guest' }}</div>
                                        <small class="text-secondary-light">{{ $review->customer->email ?? '—' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $review->product->slug ?? '#') }}" target="_blank" class="text-primary-600 fw-medium text-decoration-none">
                                    {{ Str::limit($review->product->name ?? '—', 40) }}
                                </a>
                                <div class="text-secondary-light fs-12">SKU: {{ $review->product->sku ?? 'N/A' }}</div>
                            </td>
                            <td style="min-width: 280px; max-width: 320px; white-space: normal;">
                                <p class="mb-1 text-truncate" style="max-width: 320px;">{{ $review->review_text ? Str::limit($review->review_text, 120) : 'No review text provided.' }}</p>
                                @if($review->review_text && Str::length($review->review_text) > 120)
                                    <small class="text-secondary-light">Full review available on product page.</small>
                                @endif
                            </td>
                            <td>
                                @php $rating = $review->rating; @endphp
                                <div class="d-flex align-items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ri-star-{{ $i <= $rating ? 'fill' : 'line' }} text-warning-main fs-14"></i>
                                    @endfor
                                    <span class="text-secondary-light ms-1">{{ $rating }}/5</span>
                                </div>
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
                                    <form action="{{ route('dashboard.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success-focus text-success-main px-10 py-3" title="Approve">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($review->status !== 'rejected')
                                    <form action="{{ route('dashboard.reviews.reject', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning-focus text-warning-main px-10 py-3" title="Reject">
                                            <i class="ri-close-circle-line"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('dashboard.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                        @csrf @method('DELETE')
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
                                No reviews found for the selected filters.
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
