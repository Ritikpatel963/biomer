@extends('layout.layout')
@section('title', 'Audience Preferences')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Who Are You Responses</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Audience Preferences</li>
        </ul>
    </div>

    <div class="row g-3 mb-24">
        @foreach([
            ['label' => 'Total', 'value' => $counts['all'], 'color' => 'primary'],
            ['label' => 'Kisan', 'value' => $counts['kisan'], 'color' => 'success'],
            ['label' => 'Partners', 'value' => $counts['partners'], 'color' => 'warning'],
            ['label' => 'Dealers', 'value' => $counts['dealers'], 'color' => 'info'],
        ] as $stat)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="fw-medium text-secondary-light mb-1">{{ $stat['label'] }}</p>
                        <h6 class="fw-semibold text-{{ $stat['color'] }}-main mb-0">{{ $stat['value'] }}</h6>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom">
            <h6 class="mb-0">Captured Responses</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 admin-data-table" data-page-length="10">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Audience</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Source</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preferences as $preference)
                            <tr>
                                <td class="ps-3">{{ $preference->id }}</td>
                                <td>
                                    <span class="badge bg-success-focus text-success-main">{{ ucfirst($preference->audience_type) }}</span>
                                </td>
                                <td>{{ $preference->name ?? ($preference->customer->name ?? 'Guest Visitor') }}</td>
                                <td>{{ $preference->email ?? ($preference->customer->email ?? '—') }}</td>
                                <td>{{ $preference->phone ?? ($preference->customer->phone ?? '—') }}</td>
                                <td style="max-width: 260px;">{{ $preference->source_url ?? '—' }}</td>
                                <td>{{ $preference->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary-light">No audience responses yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($preferences->hasPages())
                <div class="px-16 py-12 border-top">
                    {{ $preferences->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
