<div id="homepage-editor-panel">
    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">Homepage Editor Overview</h5>
        </div>
        <div class="admin-section-card__body">
            <p class="text-muted mb-0">Edit banner slides, section copy, and Instagram video reviews from one place.</p>
        </div>
    </div>

    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">Homepage Banner Slider</h5>
        </div>
        <div class="admin-section-card__body">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <x-toggle-switch name="hero_enabled" id="hero_enabled" :checked="$homepageConfig['hero_enabled']" label="Enable Banner" variant="rounded" />
                </div>
                <div class="col-md-3">
                    <x-toggle-switch name="hero_slider_enabled" id="hero_slider_enabled" :checked="$homepageConfig['hero_slider_enabled']" label="Enable Slider" variant="rounded" />
                </div>
            </div>
            @foreach($homepageConfig['hero_slides'] as $index => $slide)
                <div class="admin-nested-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Slide {{ $index + 1 }}</h6>
                        <x-toggle-switch
                            name="hero_slides[{{ $index }}][is_active]"
                            id="slide_active_{{ $index }}"
                            :checked="!empty($slide['is_active'])"
                            label="Active"
                            variant="rounded"
                        />
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Headline Line 1</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][headline_prefix]" value="{{ old("hero_slides.$index.headline_prefix", $slide['headline_prefix']) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Highlighted Word</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][headline_highlight]" value="{{ old("hero_slides.$index.headline_highlight", $slide['headline_highlight']) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Headline Line 2</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][headline_suffix]" value="{{ old("hero_slides.$index.headline_suffix", $slide['headline_suffix']) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="2" name="hero_slides[{{ $index }}][description]">{{ old("hero_slides.$index.description", $slide['description']) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Secondary Description</label>
                            <textarea class="form-control" rows="2" name="hero_slides[{{ $index }}][secondary_description]">{{ old("hero_slides.$index.secondary_description", $slide['secondary_description']) }}</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Primary Button Text</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][primary_button_text]" value="{{ old("hero_slides.$index.primary_button_text", $slide['primary_button_text']) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Primary Button URL</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][primary_button_url]" value="{{ old("hero_slides.$index.primary_button_url", $slide['primary_button_url']) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Secondary Button Text</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][secondary_button_text]" value="{{ old("hero_slides.$index.secondary_button_text", $slide['secondary_button_text']) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Secondary Button URL</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][secondary_button_url]" value="{{ old("hero_slides.$index.secondary_button_url", $slide['secondary_button_url']) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Media Type</label>
                            <select class="form-select" name="hero_slides[{{ $index }}][media_type]">
                                <option value="image" {{ $slide['media_type'] === 'image' ? 'selected' : '' }}>Image</option>
                                <option value="video" {{ $slide['media_type'] === 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Media URL</label>
                            <input type="text" class="form-control" name="hero_slides[{{ $index }}][media_url]" value="{{ old("hero_slides.$index.media_url", $slide['media_url']) }}" placeholder="Image URL, YouTube/Vimeo URL, or direct video file URL">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Upload Media (optional)</label>
                            <input type="file" class="form-control @error('hero_slide_media_files.' . $index) is-invalid @enderror" name="hero_slide_media_files[{{ $index }}]" accept="image/*">
                            <small class="text-muted d-block mt-1">If uploaded, this file will replace the Media URL for this slide.</small>
                            @error('hero_slide_media_files.' . $index)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">Why Bharat Biomer</h5>
        </div>
        <div class="admin-section-card__body">
            <div class="mb-3">
                <label class="form-label">Section Title</label>
                <input type="text" class="form-control" name="why_bharat_title" value="{{ old('why_bharat_title', $homepageConfig['why_bharat_title']) }}">
            </div>
            <div class="row g-3">
                @foreach($homepageConfig['why_bharat_items'] as $index => $item)
                    <div class="col-md-6">
                        <div class="admin-nested-card h-100">
                            <label class="form-label">Card {{ $index + 1 }} Title</label>
                            <input type="text" class="form-control mb-2" name="why_bharat_items[{{ $index }}][title]" value="{{ old("why_bharat_items.$index.title", $item['title']) }}">
                            <label class="form-label">Card {{ $index + 1 }} Description</label>
                            <textarea class="form-control" rows="2" name="why_bharat_items[{{ $index }}][description]">{{ old("why_bharat_items.$index.description", $item['description']) }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">What We Do</h5>
        </div>
        <div class="admin-section-card__body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="what_we_do_title" value="{{ old('what_we_do_title', $homepageConfig['what_we_do_title']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Label</label>
                    <input type="text" class="form-control" name="what_we_do_label" value="{{ old('what_we_do_label', $homepageConfig['what_we_do_label']) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="3" name="what_we_do_description">{{ old('what_we_do_description', $homepageConfig['what_we_do_description']) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Right Image URL</label>
                    <input type="text" class="form-control" name="what_we_do_image_url" value="{{ old('what_we_do_image_url', $homepageConfig['what_we_do_image_url']) }}">
                </div>
                @foreach($homepageConfig['what_we_do_items'] as $index => $item)
                    <div class="col-md-3">
                        <label class="form-label">Item {{ $index + 1 }}</label>
                        <input type="text" class="form-control" name="what_we_do_items[{{ $index }}][label]" value="{{ old("what_we_do_items.$index.label", $item['label']) }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">Who We Serve</h5>
        </div>
        <div class="admin-section-card__body">
            <div class="mb-3">
                <label class="form-label">Section Title</label>
                <input type="text" class="form-control" name="who_we_serve_title" value="{{ old('who_we_serve_title', $homepageConfig['who_we_serve_title']) }}">
            </div>
            <div class="row g-3">
                @foreach($homepageConfig['who_we_serve_items'] as $index => $item)
                    <div class="col-md-4">
                        <label class="form-label">Audience {{ $index + 1 }}</label>
                        <input type="text" class="form-control" name="who_we_serve_items[{{ $index }}][label]" value="{{ old("who_we_serve_items.$index.label", $item['label']) }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">Key Highlights</h5>
        </div>
        <div class="admin-section-card__body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="key_highlights_title" value="{{ old('key_highlights_title', $homepageConfig['key_highlights_title']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subtitle</label>
                    <input type="text" class="form-control" name="key_highlights_subtitle" value="{{ old('key_highlights_subtitle', $homepageConfig['key_highlights_subtitle']) }}">
                </div>
            </div>
            <div class="row g-3">
                @foreach($homepageConfig['key_highlights_items'] as $index => $item)
                    <div class="col-md-6">
                        <div class="admin-nested-card h-100">
                            <label class="form-label">Highlight {{ $index + 1 }} Title</label>
                            <input type="text" class="form-control mb-2" name="key_highlights_items[{{ $index }}][title]" value="{{ old("key_highlights_items.$index.title", $item['title']) }}">
                            <label class="form-label">Highlight {{ $index + 1 }} Description</label>
                            <textarea class="form-control" rows="2" name="key_highlights_items[{{ $index }}][description]">{{ old("key_highlights_items.$index.description", $item['description']) }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-section-card mt-4">
        <div class="admin-section-card__header">
            <h5 class="card-title mb-0">Instagram Video Reviews</h5>
        </div>
        <div class="admin-section-card__body">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <x-toggle-switch
                        name="video_reviews_enabled"
                        id="video_reviews_enabled"
                        :checked="$homepageConfig['video_reviews_enabled']"
                        label="Enable Section"
                        variant="rounded"
                    />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" name="video_reviews_title" value="{{ old('video_reviews_title', $homepageConfig['video_reviews_title']) }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Section Subtitle</label>
                    <input type="text" class="form-control" name="video_reviews_subtitle" value="{{ old('video_reviews_subtitle', $homepageConfig['video_reviews_subtitle']) }}">
                </div>
            </div>

            <div class="alert alert-info">
                Upload reel videos directly (recommended) or use a video URL. Up to 6 active videos are supported, and homepage currently shows the first 5.
            </div>

            <div class="row g-3">
                @foreach(range(0, 5) as $index)
                    @php $video = $homepageConfig['video_reviews_items'][$index] ?? ['title' => '', 'instagram_url' => '', 'is_active' => true, 'video_url' => '']; @endphp
                    <div class="col-md-6">
                        <div class="admin-nested-card h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Video {{ $index + 1 }}</h6>
                                <x-toggle-switch
                                    name="video_reviews_items[{{ $index }}][is_active]"
                                    id="video_active_{{ $index }}"
                                    :checked="!array_key_exists($index, $homepageConfig['video_reviews_items']) || !empty($video['is_active'])"
                                    label="Active"
                                    variant="rounded"
                                />
                            </div>
                            <label class="form-label">Tab Label</label>
                            <input type="text" class="form-control mb-2" name="video_reviews_items[{{ $index }}][title]" value="{{ old("video_reviews_items.$index.title", $video['title']) }}" placeholder="Farmer Story">
                            <label class="form-label">Upload Video (MP4/WebM/Ogg)</label>
                            <input type="file" class="form-control mb-2 @error('video_review_files.' . $index) is-invalid @enderror" name="video_review_files[{{ $index }}]" accept="video/mp4,video/webm,video/ogg">
                            @error('video_review_files.' . $index)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mb-2">If you upload a file, it will replace the URL for this reel.</small>
                            <label class="form-label">Video URL (Optional)</label>
                            <input type="text" class="form-control" name="video_reviews_items[{{ $index }}][instagram_url]" value="{{ old("video_reviews_items.$index.instagram_url", $video['instagram_url']) }}" placeholder="https://example.com/video.mp4 or stored path">
                            @if(!empty($video['instagram_url']))
                                <small class="text-muted d-block mt-2">Current saved: {{ $video['instagram_url'] }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
