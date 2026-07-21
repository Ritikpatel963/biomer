@extends('layout.layout')

@php
    $title = 'Home Page';
    $subTitle = 'Manage homepage sections';
    $blankBasicItem = ['image_path' => '', 'heading' => '', 'paragraph' => ''];
    $blankSolutionItem = ['image_path' => '', 'icon_path' => '', 'heading' => '', 'paragraph' => '', 'url' => '#'];
    $blankStatsItem = ['icon_path' => '', 'number' => '', 'heading' => '', 'paragraph' => ''];
    $blankStoryItem = ['thumbnail_path' => '', 'video_url' => '', 'duration' => '', 'heading' => '', 'paragraph' => ''];
    $problemItems = count($homePageConfig['problem_items']) ? $homePageConfig['problem_items'] : [$blankBasicItem];
    $solutionItems = count($homePageConfig['solution_items']) ? $homePageConfig['solution_items'] : [$blankSolutionItem];
    $whyItems = count($homePageConfig['why_items']) ? $homePageConfig['why_items'] : [$blankBasicItem];
    $statsItems = count($homePageConfig['stats_items']) ? $homePageConfig['stats_items'] : [$blankStatsItem];
    $storyItems = count($homePageConfig['story_items']) ? $homePageConfig['story_items'] : [$blankStoryItem];
@endphp

@section('content')

<div
    class="admin-shell"
    data-home-page-settings
    data-blank-preview="{{ e(\App\Models\HomePageSetting::imageUrl('')) }}"
>
    <div class="admin-page-card">
        <div class="admin-page-card__header">
            <div>
                <span class="admin-page-card__eyebrow">Website Setup</span>
                <!-- <h2 class="admin-page-card__title">Home Page</h2>
                <p class="admin-page-card__desc">Edit the homepage sections one by one. This step controls the farming challenges section.</p> -->
            </div>
            <div class="admin-page-card__actions">
                <a href="{{ route('dashboard.site-settings.edit') }}" class="btn btn-outline-secondary">Site Settings</a>
            </div>
        </div>

        <div class="admin-toolbar-tabs">
            <a href="{{ route('dashboard.home-page.edit') }}" class="admin-toolbar-tabs__link active">Home Page</a>
            <a href="{{ route('dashboard.site-settings.edit') }}" class="admin-toolbar-tabs__link">Site Settings</a>
            <a href="{{ route('dashboard.google-analytics.edit') }}" class="admin-toolbar-tabs__link">Google Analytics</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-4">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.home-page.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Farming Challenges Section</h5>
                        <small class="text-muted">This is the card row below the hero area.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Main Heading</label>
                            <input type="text" name="problem_heading" class="form-control @error('problem_heading') is-invalid @enderror"
                                value="{{ old('problem_heading', $homePageConfig['problem_heading']) }}"
                                placeholder="Farming today needs more than fertilizers">
                            @error('problem_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Paragraph</label>
                            <textarea name="problem_paragraph" rows="2" class="form-control @error('problem_paragraph') is-invalid @enderror"
                                placeholder="Modern farmers face multiple crop and soil challenges every season.">{{ old('problem_paragraph', $homePageConfig['problem_paragraph']) }}</textarea>
                            @error('problem_paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Challenge Cards</h5>
                        <small class="text-muted">Each card has an image, sub heading, and sub paragraph.</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add="problem">Add Card</button>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-4" data-repeater="problem">
                        @foreach($problemItems as $index => $item)
                            <div class="col-12 col-xl-6" data-repeater-item>
                                <div class="admin-nested-card h-100">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                        <h6 class="mb-0" data-repeater-title>Card {{ $index + 1 }}</h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-repeater-edit>Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-delete>Delete</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="problem_items[{{ $index }}][image_path]"
                                        value="{{ old("problem_items.$index.image_path", $item['image_path']) }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-semibold">Image</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("problem_items.$index.image_path", $item['image_path'])) }}"
                                                    alt="Card {{ $index + 1 }} image">
                                            </div>
                                            <input type="file" name="problem_item_images[{{ $index }}]"
                                                class="form-control @error('problem_item_images.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">PNG, JPG, or WEBP. Max 2MB.</small>
                                            @error('problem_item_images.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-8">
                                            <label class="form-label fw-semibold">Sub Heading</label>
                                            <input type="text" name="problem_items[{{ $index }}][heading]"
                                                class="form-control mb-3 @error('problem_items.' . $index . '.heading') is-invalid @enderror"
                                                value="{{ old("problem_items.$index.heading", $item['heading']) }}"
                                                placeholder="Soil degradation">
                                            @error('problem_items.' . $index . '.heading')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Sub Paragraph</label>
                                            <textarea name="problem_items[{{ $index }}][paragraph]" rows="3"
                                                class="form-control @error('problem_items.' . $index . '.paragraph') is-invalid @enderror"
                                                placeholder="Continuous farming reduces soil organic matter and health.">{{ old("problem_items.$index.paragraph", $item['paragraph']) }}</textarea>
                                            @error('problem_items.' . $index . '.paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Solutions by Category Section</h5>
                        <small class="text-muted">This controls the product/category cards on the homepage.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Main Heading</label>
                            <input type="text" name="solution_heading" class="form-control @error('solution_heading') is-invalid @enderror"
                                value="{{ old('solution_heading', $homePageConfig['solution_heading']) }}"
                                placeholder="Solutions by category">
                            @error('solution_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Paragraph</label>
                            <textarea name="solution_paragraph" rows="2" class="form-control @error('solution_paragraph') is-invalid @enderror"
                                placeholder="Clear, crop-focused biological inputs...">{{ old('solution_paragraph', $homePageConfig['solution_paragraph']) }}</textarea>
                            @error('solution_paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Solution Cards</h5>
                        <small class="text-muted">Each card has a main image, icon image, sub heading, sub paragraph, and optional link.</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add="solution">Add Card</button>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-4" data-repeater="solution">
                        @foreach($solutionItems as $index => $item)
                            <div class="col-12 col-xl-6" data-repeater-item>
                                <div class="admin-nested-card h-100">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                        <h6 class="mb-0" data-repeater-title>Solution Card {{ $index + 1 }}</h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-repeater-edit>Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-delete>Delete</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="solution_items[{{ $index }}][image_path]"
                                        value="{{ old("solution_items.$index.image_path", $item['image_path']) }}">
                                    <input type="hidden" name="solution_items[{{ $index }}][icon_path]"
                                        value="{{ old("solution_items.$index.icon_path", $item['icon_path']) }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-semibold">Card Image</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("solution_items.$index.image_path", $item['image_path'])) }}"
                                                    alt="Solution card {{ $index + 1 }} image">
                                            </div>
                                            <input type="file" name="solution_item_images[{{ $index }}]"
                                                class="form-control @error('solution_item_images.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">Recommended wide image. Max 4MB.</small>
                                            @error('solution_item_images.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-semibold">Icon Image</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("solution_items.$index.icon_path", $item['icon_path'])) }}"
                                                    alt="Solution card {{ $index + 1 }} icon">
                                            </div>
                                            <input type="file" name="solution_item_icons[{{ $index }}]"
                                                class="form-control @error('solution_item_icons.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">PNG, JPG, or WEBP. Max 2MB.</small>
                                            @error('solution_item_icons.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-semibold">Sub Heading</label>
                                            <input type="text" name="solution_items[{{ $index }}][heading]"
                                                class="form-control @error('solution_items.' . $index . '.heading') is-invalid @enderror"
                                                value="{{ old("solution_items.$index.heading", $item['heading']) }}"
                                                placeholder="Bio-Stimulants">
                                            @error('solution_items.' . $index . '.heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-semibold">Link URL</label>
                                            <input type="text" name="solution_items[{{ $index }}][url]"
                                                class="form-control @error('solution_items.' . $index . '.url') is-invalid @enderror"
                                                value="{{ old("solution_items.$index.url", $item['url']) }}"
                                                placeholder="/products">
                                            @error('solution_items.' . $index . '.url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Sub Paragraph</label>
                                            <textarea name="solution_items[{{ $index }}][paragraph]" rows="3"
                                                class="form-control @error('solution_items.' . $index . '.paragraph') is-invalid @enderror"
                                                placeholder="Enhance plant growth, boost immunity...">{{ old("solution_items.$index.paragraph", $item['paragraph']) }}</textarea>
                                            @error('solution_items.' . $index . '.paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Why Bharat Biomer Section</h5>
                        <small class="text-muted">This controls the trust/benefits card row.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Main Heading</label>
                            <input type="text" name="why_heading" class="form-control @error('why_heading') is-invalid @enderror"
                                value="{{ old('why_heading', $homePageConfig['why_heading']) }}"
                                placeholder="Why Bharat Biomer">
                            @error('why_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Paragraph</label>
                            <textarea name="why_paragraph" rows="2" class="form-control @error('why_paragraph') is-invalid @enderror"
                                placeholder="Built for performance, crop relevance...">{{ old('why_paragraph', $homePageConfig['why_paragraph']) }}</textarea>
                            @error('why_paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Why Cards</h5>
                        <small class="text-muted">Each card has an image, sub heading, and sub paragraph.</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add="why">Add Card</button>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-4" data-repeater="why">
                        @foreach($whyItems as $index => $item)
                            <div class="col-12 col-xl-6" data-repeater-item>
                                <div class="admin-nested-card h-100">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                        <h6 class="mb-0" data-repeater-title>Why Card {{ $index + 1 }}</h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-repeater-edit>Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-delete>Delete</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="why_items[{{ $index }}][image_path]"
                                        value="{{ old("why_items.$index.image_path", $item['image_path']) }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-semibold">Image</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("why_items.$index.image_path", $item['image_path'])) }}"
                                                    alt="Why card {{ $index + 1 }} image">
                                            </div>
                                            <input type="file" name="why_item_images[{{ $index }}]"
                                                class="form-control @error('why_item_images.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">PNG, JPG, or WEBP. Max 2MB.</small>
                                            @error('why_item_images.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-8">
                                            <label class="form-label fw-semibold">Sub Heading</label>
                                            <input type="text" name="why_items[{{ $index }}][heading]"
                                                class="form-control mb-3 @error('why_items.' . $index . '.heading') is-invalid @enderror"
                                                value="{{ old("why_items.$index.heading", $item['heading']) }}"
                                                placeholder="Research-driven formulations">
                                            @error('why_items.' . $index . '.heading')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Sub Paragraph</label>
                                            <textarea name="why_items[{{ $index }}][paragraph]" rows="3"
                                                class="form-control @error('why_items.' . $index . '.paragraph') is-invalid @enderror"
                                                placeholder="Developed using advanced R&D...">{{ old("why_items.$index.paragraph", $item['paragraph']) }}</textarea>
                                            @error('why_items.' . $index . '.paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Stats Impact Section</h5>
                        <small class="text-muted">This controls the green number cards over the field background.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-4">
                            <label class="form-label fw-semibold">Background Image</label>
                            <input type="hidden" name="stats_background_image"
                                value="{{ old('stats_background_image', $homePageConfig['stats_background_image']) }}">
                            <div class="admin-logo-preview mb-2">
                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old('stats_background_image', $homePageConfig['stats_background_image'])) }}"
                                    alt="Stats background image">
                            </div>
                            <input type="file" name="stats_background_file"
                                class="form-control @error('stats_background_file') is-invalid @enderror"
                                accept="image/*">
                            <small class="text-muted d-block mt-1">Recommended wide field image. Max 4MB.</small>
                            @error('stats_background_file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="alert alert-info mb-0">
                                Add the background image here, then edit each stat card below with icon, number, heading, and paragraph.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Stats Cards</h5>
                        <small class="text-muted">Each card has an icon, number, heading, and short paragraph.</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add="stats">Add Card</button>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-4" data-repeater="stats">
                        @foreach($statsItems as $index => $item)
                            <div class="col-12 col-xl-6" data-repeater-item>
                                <div class="admin-nested-card h-100">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                        <h6 class="mb-0" data-repeater-title>Stats Card {{ $index + 1 }}</h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-repeater-edit>Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-delete>Delete</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="stats_items[{{ $index }}][icon_path]"
                                        value="{{ old("stats_items.$index.icon_path", $item['icon_path']) }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-semibold">Icon</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("stats_items.$index.icon_path", $item['icon_path'])) }}"
                                                    alt="Stats card {{ $index + 1 }} icon">
                                            </div>
                                            <input type="file" name="stats_item_icons[{{ $index }}]"
                                                class="form-control @error('stats_item_icons.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">PNG, JPG, or WEBP. Max 2MB.</small>
                                            @error('stats_item_icons.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-8">
                                            <label class="form-label fw-semibold">Number</label>
                                            <input type="text" name="stats_items[{{ $index }}][number]"
                                                class="form-control mb-3 @error('stats_items.' . $index . '.number') is-invalid @enderror"
                                                value="{{ old("stats_items.$index.number", $item['number']) }}"
                                                placeholder="10,000+">
                                            @error('stats_items.' . $index . '.number')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Heading</label>
                                            <input type="text" name="stats_items[{{ $index }}][heading]"
                                                class="form-control mb-3 @error('stats_items.' . $index . '.heading') is-invalid @enderror"
                                                value="{{ old("stats_items.$index.heading", $item['heading']) }}"
                                                placeholder="Farmers Served">
                                            @error('stats_items.' . $index . '.heading')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Paragraph</label>
                                            <textarea name="stats_items[{{ $index }}][paragraph]" rows="2"
                                                class="form-control @error('stats_items.' . $index . '.paragraph') is-invalid @enderror"
                                                placeholder="Across India">{{ old("stats_items.$index.paragraph", $item['paragraph']) }}</textarea>
                                            @error('stats_items.' . $index . '.paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Stories from the Field Section</h5>
                        <small class="text-muted">This controls the embedded video stories row.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Main Heading</label>
                            <input type="text" name="story_heading" class="form-control @error('story_heading') is-invalid @enderror"
                                value="{{ old('story_heading', $homePageConfig['story_heading']) }}"
                                placeholder="Stories from the field">
                            @error('story_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Paragraph</label>
                            <textarea name="story_paragraph" rows="2" class="form-control @error('story_paragraph') is-invalid @enderror"
                                placeholder="Field outcomes, grower stories...">{{ old('story_paragraph', $homePageConfig['story_paragraph']) }}</textarea>
                            @error('story_paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Button Text</label>
                            <input type="text" name="story_button_text" class="form-control @error('story_button_text') is-invalid @enderror"
                                value="{{ old('story_button_text', $homePageConfig['story_button_text']) }}"
                                placeholder="View More Stories">
                            @error('story_button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Button URL</label>
                            <input type="text" name="story_button_url" class="form-control @error('story_button_url') is-invalid @enderror"
                                value="{{ old('story_button_url', $homePageConfig['story_button_url']) }}"
                                placeholder="/stories">
                            @error('story_button_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Video Story Cards</h5>
                        <small class="text-muted">Paste YouTube/Vimeo URLs or embed links. Empty cards will not show on the homepage.</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add="story">Add Video</button>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-4" data-repeater="story">
                        @foreach($storyItems as $index => $item)
                            <div class="col-12 col-xl-6" data-repeater-item>
                                <div class="admin-nested-card h-100">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                        <h6 class="mb-0" data-repeater-title>Video Story {{ $index + 1 }}</h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-repeater-edit>Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-delete>Delete</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="story_items[{{ $index }}][thumbnail_path]"
                                        value="{{ old("story_items.$index.thumbnail_path", $item['thumbnail_path']) }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-semibold">Thumbnail</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("story_items.$index.thumbnail_path", $item['thumbnail_path'])) }}"
                                                    alt="Video story {{ $index + 1 }} thumbnail">
                                            </div>
                                            <input type="file" name="story_item_thumbnails[{{ $index }}]"
                                                class="form-control @error('story_item_thumbnails.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">Recommended wide image. Max 4MB.</small>
                                            @error('story_item_thumbnails.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-8">
                                            <label class="form-label fw-semibold">Video Embed URL</label>
                                            <input type="text" name="story_items[{{ $index }}][video_url]"
                                                class="form-control mb-3 @error('story_items.' . $index . '.video_url') is-invalid @enderror"
                                                value="{{ old("story_items.$index.video_url", $item['video_url']) }}"
                                                placeholder="https://www.youtube.com/watch?v=...">
                                            @error('story_items.' . $index . '.video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Duration</label>
                                            <input type="text" name="story_items[{{ $index }}][duration]"
                                                class="form-control mb-3 @error('story_items.' . $index . '.duration') is-invalid @enderror"
                                                value="{{ old("story_items.$index.duration", $item['duration']) }}"
                                                placeholder="2:15">
                                            @error('story_items.' . $index . '.duration')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Sub Heading</label>
                                            <input type="text" name="story_items[{{ $index }}][heading]"
                                                class="form-control mb-3 @error('story_items.' . $index . '.heading') is-invalid @enderror"
                                                value="{{ old("story_items.$index.heading", $item['heading']) }}"
                                                placeholder="Tomato yield improvement">
                                            @error('story_items.' . $index . '.heading')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Sub Paragraph</label>
                                            <textarea name="story_items[{{ $index }}][paragraph]" rows="3"
                                                class="form-control @error('story_items.' . $index . '.paragraph') is-invalid @enderror"
                                                placeholder="See how Bharat Biomer solutions increased yield...">{{ old("story_items.$index.paragraph", $item['paragraph']) }}</textarea>
                                            @error('story_items.' . $index . '.paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-between mt-4 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Save Home Page</button>
            </div>
        </form>
    </div>
</div>

@endsection
