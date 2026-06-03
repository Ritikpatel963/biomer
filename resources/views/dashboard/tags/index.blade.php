{{-- resources/views/dashboard/tags/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Tags';
    $subTitle = 'Tags';
    $script   = '<script>
                    let table = new DataTable("#dataTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        columnDefs: [
                            { orderable: false, targets: [0, 5] }
                        ]
                    });

                    document.getElementById("checkAll").addEventListener("change", function () {
                        document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    });
                 </script>';
@endphp

@section('content')

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Tags</h5>
        <a href="{{ route('dashboard.tags.create') }}" class="btn btn-primary btn-sm">
            + Add Tag
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
             {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10' style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label">S.L</label>
                            </div>
                        </th>
                        <th scope="col">Tag Name</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Products Using Tag</th>
                        <th scope="col">Created</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $i => $tag)
                    <tr>
                        {{-- Checkbox + Serial --}}
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($tags->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        {{-- Tag Name --}}
                        <td>
                            <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">
                                 {{ $tag->name }}
                            </span>
                        </td>

                        {{-- Slug --}}
                        <td><code class="text-secondary-light text-sm">{{ $tag->slug }}</code></td>

                        {{-- Products Count --}}
                        <td>
                            <span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ $tag->products_count ?? 0 }} Products
                            </span>
                        </td>

                        {{-- Created --}}
                        <td>{{ $tag->created_at->format('d M Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            {{-- Edit --}}
                            <a href="{{ route('dashboard.tags.edit', $tag) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="Edit Tag">
                                Edit
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('dashboard.tags.destroy', $tag) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete tag \'{{ addslashes($tag->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Tag">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            No tags yet.
                            <a href="{{ route('dashboard.tags.create') }}">Add the first one!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>{{-- end table-responsive --}}
    </div>
</div>

@endsection
