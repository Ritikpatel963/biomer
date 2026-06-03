@extends('layout.layout')

@php
    $title = isset($product) ? 'Edit Product' : 'Product Add';
    $subTitle = 'Products';
@endphp

@push('styles')
    <style>
        :root {
            --green: #2d7a45;
            --green-light: #e8f5ed;
            --border: #dee2e6;
            --radius: 10px;
        }

        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 1.6rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .card-header {
            padding: .9rem 1.4rem;
            border-bottom: 1px solid var(--border);
            background: var(--green-light);
            border-radius: var(--radius) var(--radius) 0 0;
            font-weight: 600;
            color: var(--green);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .card-body {
            padding: 1.4rem;
        }

        .form-label {
            font-weight: 500;
            font-size: .875rem;
            color: #495057;
            margin-bottom: .35rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 7px;
            font-size: .9rem;
            padding: .5rem .75rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(45, 122, 69, .15);
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
        }

        .variation-builder {
            border: 1px solid #dce8df;
            border-radius: 8px;
            background: #f8fbf9;
            padding: 1rem;
        }

        .variation-row {
            position: relative;
            border: 1px solid #dce8df;
            border-radius: 8px;
            background: #fff;
            padding: 1rem;
            margin-bottom: .85rem;
        }

        .variation-row .remove-variation {
            position: absolute;
            top: .75rem;
            right: .75rem;
            width: 32px;
            height: 32px;
            border: 1px solid #f1c7c7;
            border-radius: 7px;
            background: #fff5f5;
            color: #dc3545;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .variation-choice-grid {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .variation-choice {
            border: 1px solid #cfe3d5;
            background: #fff;
            border-radius: 8px;
            padding: .45rem .7rem;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin: 0;
            cursor: pointer;
        }

        .variation-choice input {
            margin: 0;
        }

        .variation-table-wrap {
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .variation-table {
            margin: 0;
            min-width: 920px;
        }

        .variation-table th {
            background: #f4f8f5;
            color: #3f5144;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .variation-table td {
            vertical-align: middle;
        }

        .variation-table .form-control,
        .variation-table .form-select {
            min-width: 110px;
        }

        .img-preview-grid {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: .75rem;
        }

        .img-preview-grid .preview-thumb {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid var(--border);
        }

        .tag-pills {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-top: .5rem;
        }

        .tag-pill {
            background: var(--green-light);
            color: var(--green);
            border: 1px solid #a8d5b5;
            border-radius: 20px;
            padding: .2rem .7rem;
            font-size: .8rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .tag-pill button {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: .9rem;
            padding: 0;
            line-height: 1;
        }

        .btn-primary {
            background: var(--green);
            border-color: var(--green);
            padding: .55rem 1.6rem;
            border-radius: 7px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #1a5c30;
            border-color: #1a5c30;
        }

        .btn-outline-secondary {
            border-radius: 7px;
        }

        .existing-img {
            position: relative;
            display: inline-block;
        }

        .featured-img-wrap {
            position: relative;
            display: block;
            margin-bottom: 12px;
        }

        .existing-img img,
        .featured-img-wrap img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid var(--border);
        }

        .featured-img-wrap img {
            width: 100%;
            height: 180px;
        }

        .existing-img .del-img,
        .featured-img-wrap .del-img {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ff4d4f;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: .7rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .invalid-feedback {
            display: block;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .var-badge {
            display: inline-block;
            background: var(--green-light);
            color: var(--green);
            border: 1px solid #b9dbc4;
            border-radius: 7px;
            padding: .2rem .6rem;
            font-size: .75rem;
            font-weight: 700;
            margin-bottom: .75rem;
        }

        .wc-product-data {
            overflow: hidden;
        }

        .wc-data-layout {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            min-height: 420px;
        }

        .wc-data-tabs {
            background: #f8fafc;
            border-right: 1px solid var(--border);
            padding: 14px;
        }

        .wc-data-tab {
            width: 100%;
            border: 0;
            background: transparent;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 44px;
            padding: 10px 12px;
            border-radius: 8px;
            font-weight: 700;
            text-align: left;
        }

        .wc-data-tab.active,
        .wc-data-tab:hover {
            background: #e8f5ed;
            color: var(--green);
        }

        .wc-data-tab span {
            margin-left: auto;
            min-width: 24px;
            height: 24px;
            border-radius: 999px;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
        }

        .wc-data-content {
            padding: 18px;
            min-width: 0;
        }

        .wc-tab-panel {
            display: none;
        }

        .wc-tab-panel.active {
            display: block;
        }

        .wc-panel-toolbar,
        .wc-defaults-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-bottom: 16px;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .wc-attribute-list,
        .wc-variation-list {
            display: grid;
            gap: 12px;
        }

        .wc-attribute-item,
        .variation-row {
            border: 1px solid #dce8df;
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
            padding: 0;
        }

        .wc-attribute-heading,
        .wc-variation-heading {
            width: 100%;
            border: 0;
            background: #f8fbf9;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            color: #17311f;
            font-weight: 800;
            text-align: left;
        }

        .wc-attribute-heading small {
            color: #64748b;
            font-weight: 600;
        }

        .wc-attribute-heading iconify-icon,
        .wc-row-toggle iconify-icon {
            margin-left: auto;
            transition: transform .2s ease;
        }

        .wc-attribute-item:not(.is-open) .wc-attribute-body,
        .variation-row:not(.is-open) .wc-variation-body {
            display: none;
        }

        .wc-attribute-item.is-open .wc-attribute-heading iconify-icon,
        .variation-row.is-open .wc-row-toggle iconify-icon {
            transform: rotate(180deg);
        }

        .wc-attribute-body,
        .wc-variation-body {
            padding: 16px;
        }

        .wc-value-box,
        .wc-checkbox-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .wc-value-pill,
        .wc-checkbox-grid label {
            border: 1px solid #dce8df;
            background: #fbfdf9;
            border-radius: 8px;
            min-height: 38px;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            cursor: pointer;
        }

        .wc-variation-heading {
            justify-content: space-between;
        }

        .wc-variation-title,
        .wc-row-toggle {
            border: 0;
            background: transparent;
            color: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            padding: 0;
        }

        .wc-variation-actions {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .wc-empty-variations {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            color: #64748b;
            padding: 24px;
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .wc-data-layout {
                grid-template-columns: 1fr;
            }

            .wc-data-tabs {
                border-right: 0;
                border-bottom: 1px solid var(--border);
            }
        }

        .product-editor {
            --pe-green: #2d7a45;
            --pe-green-dark: #1d5f35;
            --pe-ink: #111827;
            --pe-muted: #64748b;
            --pe-line: #dfe7ee;
            --pe-radius: 14px;
            padding: 20px 18px 32px;
            background: #f8faf9;
        }

        .product-editor .product-editor__main,
        .product-editor .product-editor__sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .product-editor .card {
            border: 1px solid var(--pe-line);
            border-radius: var(--pe-radius);
            box-shadow: 0 14px 30px rgba(15, 23, 42, .05);
            margin-bottom: 0 !important;
            overflow: hidden;
        }

        .product-editor .card-header {
            background: #ffffff;
            border-bottom: 1px solid #edf2f7;
            color: var(--pe-ink);
            padding: 17px 20px;
            font-size: 15px;
            font-weight: 800;
            border-radius: var(--pe-radius) var(--pe-radius) 0 0;
        }

        .product-editor .card-header::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--pe-green);
            box-shadow: 0 0 0 4px #e7f5ec;
            flex: 0 0 auto;
        }

        .product-editor .card-body {
            padding: 22px;
        }

        .product-editor .form-label {
            color: #334155;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .product-editor .form-control,
        .product-editor .form-select {
            min-height: 44px;
            border-color: #d7e1e8;
            border-radius: 10px;
            background-color: #ffffff;
            color: var(--pe-ink);
            font-size: 14px;
            padding: 9px 12px;
            box-shadow: none;
        }

        .product-editor .form-control:focus,
        .product-editor .form-select:focus {
            border-color: var(--pe-green);
            box-shadow: 0 0 0 4px rgba(45, 122, 69, .12);
        }

        .product-editor textarea.form-control {
            min-height: 118px;
        }

        .product-editor small,
        .product-editor .text-muted,
        .product-editor .text-secondary-light {
            color: var(--pe-muted) !important;
        }

        .product-editor .btn {
            border-radius: 10px;
            font-weight: 800;
            min-height: 40px;
        }

        .product-editor .btn-lg {
            min-height: 48px;
        }

        .product-editor .btn-outline-secondary {
            color: #334155;
            border-color: #cbd5e1;
            background: #ffffff;
        }

        .product-editor .btn-outline-secondary:hover {
            background: #f1f5f9;
            color: var(--pe-ink);
        }

        .product-editor .input-group .form-control {
            border-radius: 10px 0 0 10px;
        }

        .product-editor .input-group .btn {
            border-radius: 0 10px 10px 0;
        }

        .product-editor .img-fluid.rounded {
            border-radius: 12px !important;
            border: 1px solid #dfe7ee;
            background: #f8fafc;
        }

        .product-editor .img-preview-grid .preview-thumb,
        .product-editor .existing-img img {
            width: 96px;
            height: 96px;
            border-radius: 12px;
            border: 1px solid #dfe7ee;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .08);
        }

        .product-editor .featured-img-wrap img {
            height: 180px;
            border-radius: 12px;
            border: 1px solid #dfe7ee;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .08);
        }

        .product-editor .existing-img .del-img,
        .product-editor .featured-img-wrap .del-img {
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border: 2px solid #ffffff;
            font-weight: 800;
        }

        .product-editor .tag-pill {
            background: #e7f5ec;
            color: var(--pe-green-dark);
            border-color: #bfe2ca;
            border-radius: 999px;
            font-weight: 800;
            padding: 6px 10px;
        }

        .product-editor .badge.bg-light {
            border-color: #d7e1e8 !important;
            border-radius: 999px;
            padding: 6px 10px;
        }

        .product-editor .wc-product-data > .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f1fbf4 100%) !important;
        }

        .product-editor .wc-data-layout {
            grid-template-columns: 230px minmax(0, 1fr);
            min-height: 500px;
            background: #ffffff;
        }

        .product-editor .wc-data-tabs {
            background: #f7faf8;
            border-right: 1px solid #edf2f7;
            padding: 18px;
        }

        .product-editor .wc-data-tab {
            min-height: 48px;
            border-radius: 11px;
            color: #475569;
            font-size: 14px;
        }

        .product-editor .wc-data-tab.active,
        .product-editor .wc-data-tab:hover {
            background: #e7f5ec;
            color: var(--pe-green-dark);
        }

        .product-editor .wc-data-tab span {
            background: #ffffff;
            border: 1px solid #d7e1e8;
            color: var(--pe-green-dark);
            font-weight: 800;
        }

        .product-editor .wc-data-content {
            padding: 22px;
        }

        .product-editor .wc-panel-toolbar,
        .product-editor .wc-defaults-row {
            align-items: center;
            background: #f8faf9;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 18px;
        }

        .product-editor .wc-attribute-list,
        .product-editor .wc-variation-list {
            gap: 14px;
        }

        .product-editor .wc-attribute-item,
        .product-editor .variation-row {
            border: 1px solid #dfe7ee;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        }

        .product-editor .wc-attribute-heading,
        .product-editor .wc-variation-heading {
            background: #ffffff;
            color: var(--pe-ink);
            padding: 16px 18px;
        }

        .product-editor .wc-attribute-heading {
            border-bottom: 1px solid #edf2f7;
        }

        .product-editor .wc-variation-heading {
            display: flex;
            gap: 14px;
            border-bottom: 1px solid #edf2f7;
        }

        .product-editor .wc-variation-actions .remove-variation {
            position: static;
            width: auto;
            height: auto;
            background: #ffffff;
            display: inline-flex;
            padding: 6px 10px;
        }

        .product-editor .wc-variation-title {
            min-width: 0;
            flex: 1 1 auto;
        }

        .product-editor .wc-variation-title span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-editor .wc-attribute-body,
        .product-editor .wc-variation-body {
            padding: 18px;
            background: #fbfdfc;
        }

        .product-editor .wc-value-pill,
        .product-editor .wc-checkbox-grid label {
            border-color: #d7e1e8;
            background: #ffffff;
            border-radius: 999px;
            min-height: 40px;
            font-weight: 700;
        }

        .product-editor .wc-empty-variations {
            border-radius: 12px;
            background: #f8fafc;
            padding: 30px;
        }

        @media (min-width: 992px) {
            .product-editor .product-editor__sidebar {
                position: sticky;
                top: 86px;
                align-self: flex-start;
            }
        }

        @media (max-width: 991.98px) {
            .product-editor {
                padding: 16px 12px 26px;
            }

        }

        @media (max-width: 767.98px) {
            .product-editor .card-body,
            .product-editor .wc-data-content {
                padding: 16px;
            }

            .product-editor .wc-data-layout {
                grid-template-columns: 1fr;
            }

            .product-editor .wc-data-tabs {
                border-right: 0;
                border-bottom: 1px solid #edf2f7;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .product-editor .wc-variation-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="product-editor">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form
            action="{{ isset($product) ? route('dashboard.products.update', $product) : route('dashboard.products.store') }}"
            method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-8 product-editor__main">

                    {{-- Basic Info --}}
                    <div class="card mb-4">
                        <div class="card-header">Basic Information</div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product->name ?? '') }}"
                                        placeholder="e.g. Bhoomi Star" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror"
                                        value="{{ old('sku', $product->sku ?? '') }}"
                                        placeholder="Optional">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">- Select Category -</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">- Select Brand -</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Technical Content</label>
                                    <input type="text" name="technical_content" class="form-control"
                                        value="{{ old('technical_content', $product->technical_content ?? '') }}"
                                        placeholder="e.g. BIO SEA WEED EXTRACT">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'draft' => 'Draft'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('status', $product->status ?? 'active') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2"
                                        placeholder="One-line summary shown in listings...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Full Description</label>
                                    <textarea name="description" class="form-control" rows="6"
                                        placeholder="Detailed product description...">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">YouTube / Video URL</label>
                                    <input type="url" name="video_url"
                                        class="form-control @error('video_url') is-invalid @enderror"
                                        value="{{ old('video_url', $product->video_url ?? '') }}"
                                        placeholder="https://youtube.com/shorts/...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PRODUCT DATA / VARIATIONS --}}
                    @include('dashboard.products.partials.variation-builder')

                    {{-- SEO --}}
                    <div class="card mb-4">
                        <div class="card-header">SEO Settings</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Meta Title <span class="text-muted">(50-60 chars)</span></label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title', $product->meta_title ?? '') }}"
                                        placeholder="e.g., Premium Organic Bhoomi Star | Bharat Biomer"
                                        maxlength="60">
                                    <small class="text-muted">Used in search results and browser tabs</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Meta Description <span class="text-muted">(150-160 chars)</span></label>
                                    <textarea name="meta_description" class="form-control" rows="3"
                                        placeholder="e.g., Discover premium Bhoomi Star for better soil health. Natural ingredients, proven results..."
                                        maxlength="160">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                                    <small class="text-muted">Appears below title in search results</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Meta Keywords <span class="text-muted">(comma-separated)</span></label>
                                    <textarea name="meta_keyword" class="form-control" rows="2"
                                        placeholder="e.g., organic fertilizer, soil enhancement, bhoomi star, bio products, agriculture">
{{ old('meta_keyword', $product->meta_keyword ?? '') }}</textarea>
                                    <small class="text-muted">Separate keywords with commas. Not displayed but helps search engines.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /col-lg-8 --}}


                {{-- RIGHT COLUMN --}}
                <div class="col-lg-4 product-editor__sidebar">

                    {{-- Pricing --}}
                    <div class="card mb-4">
                        <div class="card-header">Pricing</div>
                        <div class="card-body">
                            <label class="form-label">Base / Starting Price (INR) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="base_price"
                                class="form-control @error('base_price') is-invalid @enderror"
                                value="{{ old('base_price', $product->base_price ?? '') }}"
                                min="0" step="0.01" placeholder="500.00" required>
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Each variation has its own price set above.</small>

                            <label class="form-label mt-3">Default Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit"
                                class="form-control @error('unit') is-invalid @enderror"
                                value="{{ old('unit', $product->unit ?? 'kg') }}"
                                placeholder="e.g. kg, liter, piece, ton, etc" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Example: kg, liter, piece, ton, box, etc. Each variation can have its own unit.</small>

                            <label class="form-label mt-3">Shipping Charge (INR)</label>
                            <input type="number" name="shipping_charge"
                                class="form-control @error('shipping_charge') is-invalid @enderror"
                                value="{{ old('shipping_charge', $product->shipping_charge ?? 0) }}"
                                min="0" step="0.01" placeholder="0.00">
                            @error('shipping_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Leave as 0 for free shipping, or enter the shipping charge amount.</small>

                            <label class="form-label mt-3">Tax Rate (%) (GST/VAT)</label>
                            <input type="number" name="tax_rate"
                                class="form-control @error('tax_rate') is-invalid @enderror"
                                value="{{ old('tax_rate', $product->tax_rate ?? 0) }}"
                                min="0" max="100" step="0.01" placeholder="0.00">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">e.g., 5% GST, 18% VAT. Leave as 0 for no tax.</small>

                            <div class="border-top mt-4 pt-4">
                                <input type="hidden" name="manage_stock" value="0">
                                <label class="form-check d-flex align-items-center gap-2 p-0 mb-3">
                                    <input type="checkbox" name="manage_stock" value="1" class="form-check-input"
                                        {{ old('manage_stock', $product->manage_stock ?? true) ? 'checked' : '' }}>
                                    <span>Manage stock for this product</span>
                                </label>

                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="stock_quantity"
                                    class="form-control @error('stock_quantity') is-invalid @enderror"
                                    value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"
                                    min="0" step="1" placeholder="0">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">For variable products, each variation stock is managed in Product Data.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="card mb-4">
                        <div class="card-header">Featured Image</div>
                        <div class="card-body">
                            @if (isset($product) && $product->featured_image)
                                <div class="featured-img-wrap" id="featuredExistingWrap">
                                    <img src="{{ Storage::url($product->featured_image) }}"
                                        class="img-fluid rounded"
                                        style="max-height:180px;object-fit:cover;width:100%;">
                                    <button type="button" class="del-img"
                                        onclick="deleteFeaturedImage({{ $product->id }}, this)"
                                        title="Remove">x</button>
                                </div>
                            @endif
                            <input type="file" name="featured_image" id="featuredImageInput" class="form-control" accept="image/*"
                                onchange="previewImage(this, 'featuredPreview')">
                            <div class="featured-img-wrap mt-2" id="featuredPreviewWrap" style="display:none;">
                                <img id="featuredPreview" class="img-fluid rounded"
                                    style="max-height:180px;object-fit:cover;width:100%;">
                                <button type="button" class="del-img" onclick="clearFeaturedPreview()" title="Remove">x</button>
                            </div>
                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div class="card mb-4">
                        <div class="card-header">Gallery Images</div>
                        <div class="card-body">
                            @if (isset($product) && $product->images->isNotEmpty())
                                <div class="img-preview-grid mb-2" id="existingGallery">
                                    @foreach ($product->images as $img)
                                        <div class="existing-img" id="existingImg_{{ $img->id }}">
                                            <img src="{{ Storage::url($img->image_path) }}">
                                            <button type="button" class="del-img"
                                                onclick="deleteImage({{ $img->id }}, this)"
                                                title="Remove">x</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple
                                onchange="previewGallery(this)">
                            <div id="galleryPreviews" class="img-preview-grid"></div>
                            <small class="text-muted">Select multiple images (Ctrl+click).</small>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card mb-4">
                        <div class="card-header">Tags</div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" id="tagInput" class="form-control"
                                    placeholder="Type a tag and press Enter">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="addTag()">Add</button>
                            </div>
                            <div class="tag-pills mt-2" id="tagPills"></div>
                            <div id="tagInputsContainer"></div>

                            @if (isset($product))
                                <script>
                                    window._existingTags = @json($product->tags->pluck('name'));
                                </script>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted d-block mb-1">Suggestions:</small>
                                <div style="display:flex;flex-wrap:wrap;gap:.3rem;">
                                    @foreach ($tags->take(20) as $t)
                                        <span class="badge bg-light text-dark border"
                                            style="cursor:pointer;font-size:.75rem;"
                                            onclick="addTagByName('{{ addslashes($t->name) }}')">{{ $t->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card mb-4">
                        <div class="card-body d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                 {{ isset($product) ? 'Update Product' : 'Save Product' }}
                            </button>
                            <a href="{{ route('dashboard.products.index') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>

                </div>{{-- /col-lg-4 --}}
            </div>{{-- /row --}}
        </form>
    </div>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var builder = document.getElementById('productVariationBuilder');
        if (!builder) return;

        var panels = builder.querySelectorAll('.wc-tab-panel');
        var tabs = builder.querySelectorAll('.wc-data-tab');
        var list = document.getElementById('variationsTableBody');
        var emptyRow = document.getElementById('emptyVariationsRow');
        var countLabel = document.getElementById('variationCountLabel');

        function activateTab(panelId) {
            tabs.forEach(function (tab) {
                tab.classList.toggle('active', tab.dataset.wcTab === panelId);
            });
            panels.forEach(function (panel) {
                panel.classList.toggle('active', panel.id === panelId);
            });
        }

        function updateBuilderEmptyState() {
            var total = list ? list.querySelectorAll('.variation-row').length : 0;
            if (emptyRow) emptyRow.classList.toggle('d-none', total > 0);
            if (countLabel) countLabel.textContent = total;
        }

        function slugPart(value) {
            return String(value || '')
                .trim()
                .replace(/[^a-zA-Z0-9]+/g, '-')
                .replace(/^-|-$/g, '')
                .toUpperCase();
        }

        function selectedAttributeSets() {
            return Array.from(builder.querySelectorAll('.variation-attribute-toggle:checked')).map(function (toggle) {
                var card = toggle.closest('.attribute-card');
                var values = Array.from(card.querySelectorAll('.variation-value-toggle:checked')).map(function (input) {
                    return input.value;
                });

                return {
                    name: toggle.dataset.attributeName,
                    values: values
                };
            }).filter(function (attribute) {
                return attribute.values.length > 0;
            });
        }

        function combinations(sets) {
            return sets.reduce(function (carry, set) {
                var next = [];
                carry.forEach(function (combo) {
                    set.values.forEach(function (value) {
                        var merged = Object.assign({}, combo);
                        merged[set.name] = value;
                        next.push(merged);
                    });
                });
                return next;
            }, [{}]);
        }

        function variationName(attributes) {
            return Object.keys(attributes).map(function (name) {
                return name + ': ' + attributes[name];
            }).join(' / ');
        }

        function addVariationRow(attributes) {
            if (!list) return;

            var index = window.varIndex++;
            var name = variationName(attributes);
            var productSku = document.querySelector('[name="sku"]')?.value || 'PRODUCT';
            var basePrice = document.querySelector('[name="base_price"]')?.value || '';
            var unit = document.querySelector('[name="unit"]')?.value || '';
            var sku = slugPart(productSku + '-' + Object.values(attributes).join('-'));
            var hiddenAttributes = Object.keys(attributes).map(function (attributeName) {
                return '<input type="hidden" name="variations[' + index + '][attributes][' + attributeName.replace(/"/g, '&quot;') + ']" value="' + String(attributes[attributeName]).replace(/"/g, '&quot;') + '">';
            }).join('');

            var row = document.createElement('div');
            row.className = 'variation-row is-open';
            row.innerHTML =
                '<div class="wc-variation-heading">' +
                    '<input type="hidden" name="variations[' + index + '][id]" value="">' +
                    '<input type="hidden" name="variations[' + index + '][name]" value="' + name.replace(/"/g, '&quot;') + '">' +
                    hiddenAttributes +
                    '<button type="button" class="wc-variation-title">' +
                        '<iconify-icon icon="lucide:grip-vertical"></iconify-icon>' +
                        '<span>#New ' + name + '</span>' +
                    '</button>' +
                    '<div class="wc-variation-actions">' +
                        '<span class="badge bg-success-100 text-success-600">Enabled</span>' +
                        '<button type="button" class="btn btn-sm btn-outline-danger-600 remove-variation">Remove</button>' +
                        '<button type="button" class="wc-row-toggle" aria-label="Toggle variation"><iconify-icon icon="lucide:chevron-down"></iconify-icon></button>' +
                    '</div>' +
                '</div>' +
                '<div class="wc-variation-body">' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6"><label class="form-label fw-bold">SKU</label><input type="text" name="variations[' + index + '][sku]" class="form-control" value="' + sku + '" placeholder="Auto generated if empty"></div>' +
                        '<div class="col-md-3"><label class="form-label fw-bold">MRP / Compare (INR)</label><input type="number" step="0.01" min="0" name="variations[' + index + '][compare_at_price]" class="form-control" placeholder="MRP"><input type="hidden" name="variations[' + index + '][cost_price]" value=""></div>' +
                        '<div class="col-md-3"><label class="form-label fw-bold">Selling Price (INR)</label><input type="number" step="0.01" min="0" name="variations[' + index + '][price]" class="form-control" value="' + basePrice + '" required></div>' +
                        '<div class="col-md-4"><label class="form-label fw-bold">Stock Quantity</label><input type="hidden" name="variations[' + index + '][track_stock]" value="0"><input type="hidden" name="variations[' + index + '][is_in_stock]" value="0"><input type="number" min="0" name="variations[' + index + '][stock_qty]" class="form-control" value="0"></div>' +
                        '<input type="hidden" name="variations[' + index + '][unit]" value="' + unit.replace(/"/g, '&quot;') + '">' +
                        '<div class="col-md-8"><label class="form-label fw-bold">Variation Settings</label><div class="wc-checkbox-grid">' +
                            '<input type="hidden" name="variations[' + index + '][track_stock]" value="1">' +
                            '<input type="hidden" name="variations[' + index + '][is_in_stock]" value="1">' +
                            '<input type="hidden" name="variations[' + index + '][is_active]" value="0">' +
                            '<input type="hidden" class="variation-default-hidden" name="variations[' + index + '][is_default]" value="0">' +
                            '<label><input class="form-check-input variation-default-radio" type="radio" name="default_variation_row" value="' + index + '"> Default variation</label>' +
                            '<label><input class="form-check-input" type="checkbox" name="variations[' + index + '][is_active]" value="1" checked> Enabled</label>' +
                        '</div></div>' +
                    '</div>' +
                '</div>';

            list.appendChild(row);
            updateBuilderEmptyState();
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activateTab(this.dataset.wcTab);
            });
        });

        document.getElementById('goToVariationsBtn')?.addEventListener('click', function () {
            activateTab('variationsPanel');
        });

        builder.querySelectorAll('.wc-attribute-heading').forEach(function (button) {
            button.addEventListener('click', function () {
                this.closest('.wc-attribute-item').classList.toggle('is-open');
            });
        });

        builder.addEventListener('click', function (event) {
            if (event.target.closest('.wc-row-toggle') || event.target.closest('.wc-variation-title')) {
                event.target.closest('.variation-row')?.classList.toggle('is-open');
            }

            if (event.target.closest('.remove-variation') && !event.target.closest('[onclick]')) {
                event.target.closest('.variation-row')?.remove();
                updateBuilderEmptyState();
            }
        });

        document.getElementById('generateVariationsBtn')?.addEventListener('click', function () {
            var sets = selectedAttributeSets();
            if (!sets.length) {
                alert('Select at least one attribute value.');
                return;
            }

            combinations(sets).forEach(addVariationRow);
            activateTab('variationsPanel');
        });

        builder.addEventListener('change', function (event) {
            if (!event.target.classList.contains('variation-default-radio')) return;
            builder.querySelectorAll('.variation-default-hidden').forEach(function (input) {
                input.value = '0';
            });
            var hidden = event.target.closest('.wc-checkbox-grid')?.querySelector('.variation-default-hidden');
            if (hidden) hidden.value = '1';
        });

        updateBuilderEmptyState();
    });

    window.varIndex = {{ isset($product) ? $product->variations->count() : 0 }};

    function removeExistingVariation(id, btn) {
        if (!confirm('Remove this variation?')) return;
        fetch('/dashboard/product-variations/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                btn.closest('.variation-row').remove();
                var countLabel = document.getElementById('variationCountLabel');
                var emptyRow = document.getElementById('emptyVariationsRow');
                var total = document.querySelectorAll('#variationsTableBody .variation-row').length;
                if (countLabel) countLabel.textContent = total;
                if (emptyRow) emptyRow.classList.toggle('d-none', total > 0);
            }
        })
        .catch(function() { alert('Error removing variation. Try again.'); });
    }
    // Featured image preview
    function previewImage(input, previewId) {
        var prev = document.getElementById(previewId);
        var wrap = document.getElementById('featuredPreviewWrap');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                prev.src = e.target.result;
                if (wrap) wrap.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearFeaturedPreview() {
        var input = document.getElementById('featuredImageInput');
        var wrap = document.getElementById('featuredPreviewWrap');
        var preview = document.getElementById('featuredPreview');
        if (input) input.value = '';
        if (preview) preview.removeAttribute('src');
        if (wrap) wrap.style.display = 'none';
    }

    function deleteFeaturedImage(productId, btn) {
        if (!confirm('Remove featured image?')) return;
        fetch('/dashboard/products/' + productId + '/featured-image', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                var wrap = btn.closest('.featured-img-wrap');
                if (wrap) wrap.remove();
            }
        })
        .catch(function() { alert('Error removing featured image. Try again.'); });
    }

    // Gallery preview
    function previewGallery(input) {
        var container = document.getElementById('galleryPreviews');
        container.innerHTML = '';
        Array.from(input.files).forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var wrap = document.createElement('div');
                wrap.className = 'existing-img';
                wrap.dataset.previewIndex = index;
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-thumb';
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'del-img';
                remove.title = 'Remove';
                remove.textContent = 'x';
                remove.onclick = function() {
                    removeGalleryPreview(index);
                };
                wrap.appendChild(img);
                wrap.appendChild(remove);
                container.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeGalleryPreview(indexToRemove) {
        var input = document.querySelector('input[name="gallery[]"]');
        if (!input || !input.files) return;

        var transfer = new DataTransfer();
        Array.from(input.files).forEach(function(file, index) {
            if (index !== indexToRemove) transfer.items.add(file);
        });
        input.files = transfer.files;
        previewGallery(input);
    }

    // Delete existing gallery image via AJAX
    function deleteImage(id, btn) {
        if (!confirm('Remove this image?')) return;
        fetch('/dashboard/product-images/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) document.getElementById('existingImg_' + id).remove();
        })
        .catch(function() { alert('Error removing image. Try again.'); });
    }

    // â”€â”€ TAG MANAGEMENT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    var activeTags = new Set();

    function renderTags() {
        var pills  = document.getElementById('tagPills');
        var inputs = document.getElementById('tagInputsContainer');
        pills.innerHTML  = '';
        inputs.innerHTML = '';

        activeTags.forEach(function(tag) {
            var safeName = tag.replace(/\\/g, '\\\\').replace(/'/g, "\\'");

            var pill = document.createElement('span');
            pill.className = 'tag-pill';
            pill.innerHTML = tag + '<button type="button" onclick="removeTag(\'' + safeName + '\')">&#x2715;</button>';
            pills.appendChild(pill);

            var hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'tags[]';
            hidden.value = tag;
            inputs.appendChild(hidden);
        });
    }

    function addTagByName(name) {
        if (name && !activeTags.has(name)) {
            activeTags.add(name);
            renderTags();
        }
    }

    function addTag() {
        var input = document.getElementById('tagInput');
        var val   = input.value.trim();
        if (val && !activeTags.has(val)) {
            activeTags.add(val);
            renderTags();
        }
        input.value = '';
    }

    function removeTag(name) {
        activeTags.delete(name);
        renderTags();
    }

    document.getElementById('tagInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag();
        }
    });

    // Pre-fill existing tags in edit mode
    (window._existingTags || []).forEach(function(t) { activeTags.add(t); });
    renderTags();
</script>
@endpush
