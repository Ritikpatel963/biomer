@extends('layout.layout')

@php
    $title    = isset($blog) ? 'Edit Blog' : 'Add Blog';
    $subTitle = isset($blog) ? 'Edit Blog' : 'Add Blog';
    $script = <<<'SCRIPT'
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
    $(document).ready(function () {

        $("#description").summernote({
            height: 300,
            placeholder: "Write your post content here...",
            toolbar: [
                ["style",  ["style"]],
                ["font",   ["bold", "italic", "underline", "strikethrough", "clear"]],
                ["color",  ["color"]],
                ["para",   ["ul", "ol", "paragraph"]],
                ["table",  ["table"]],
                ["insert", ["link", "picture", "video"]],
                ["view",   ["fullscreen", "codeview"]],
            ],
        });

        const fileInput            = document.getElementById("upload-file");
        const imagePreview         = document.getElementById("uploaded-img__preview");
        const uploadedImgContainer = document.querySelector(".uploaded-img");
        const removeButton         = document.querySelector(".uploaded-img__remove");

        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length) {
                imagePreview.src = URL.createObjectURL(e.target.files[0]);
                uploadedImgContainer.classList.remove("d-none");
            }
        });

        removeButton.addEventListener("click", () => {
            imagePreview.src = "";
            uploadedImgContainer.classList.add("d-none");
            fileInput.value  = "";
        });
    });
</script>
SCRIPT;
@endphp

@section('content')

<div class="row gy-4">

    <div class="col-lg-8">
        <div class="admin-page-card">
            <div class="admin-page-card__header">
                <div>
                    <span class="admin-page-card__eyebrow">Content Editor</span>
                    <h2 class="admin-page-card__title">{{ isset($blog) ? 'Edit Blog Post' : 'Add Blog Post' }}</h2>
                    <p class="admin-page-card__desc">Use the same admin card layout while editing content, SEO fields, status, and thumbnail assets.</p>
                </div>
                <div class="admin-page-card__actions">
                    <a href="{{ route('blog') }}" class="btn btn-outline-secondary">Back To Posts</a>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <h6 class="text-xl mb-0">{{ isset($blog) ? 'Edit Post' : 'Add New Post' }}</h6>
                </div>
                <div class="admin-section-card__body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-20" role="alert">
                        <ul class="mb-0 ps-16">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ isset($blog) ? route('updateBlog', $blog->id) : route('storeBlog') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="d-flex flex-column gap-20">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="title">
                            Post Title <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control border border-neutral-200 radius-8 @error('title') is-invalid @enderror"
                               id="title" name="title"
                               value="{{ old('title', $blog->title ?? '') }}"
                               placeholder="Enter Post Title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="category_id">
                            Post Category <span class="text-danger">*</span>
                        </label>
                        <select class="form-select border border-neutral-200 radius-8 @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="status">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select border border-neutral-200 radius-8 @error('status') is-invalid @enderror"
                                id="status" name="status">
                            <option value="draft"     {{ old('status', $blog->status ?? 'draft') == 'draft'     ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status ?? '')      == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tags --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="tags">
                            Tags <span class="text-neutral-400 fw-normal text-sm">(comma separated)</span>
                        </label>
                        <input type="text"
                               class="form-control border border-neutral-200 radius-8"
                               id="tags" name="tags"
                               value="{{ old('tags', $blog->tags ?? '') }}"
                               placeholder="e.g. technology, business, design">
                    </div>

                    {{-- Author --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="author">
                            Author
                        </label>
                        <input type="text"
                               class="form-control border border-neutral-200 radius-8"
                               id="author" name="author"
                               value="{{ old('author', $blog->author ?? '') }}"
                               placeholder="Enter author name">
                    </div>

                    {{-- Reading Time --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="reading_time">
                            Reading Time (minutes)
                        </label>
                        <input type="number"
                               class="form-control border border-neutral-200 radius-8"
                               id="reading_time" name="reading_time"
                               value="{{ old('reading_time', $blog->reading_time ?? 5) }}"
                               min="1" max="120">
                    </div>

                    {{-- SEO Meta Title --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="meta_title">
                            Meta Title
                        </label>
                        <input type="text"
                               class="form-control border border-neutral-200 radius-8"
                               id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                               placeholder="SEO meta title for this blog post">
                    </div>

                    {{-- SEO Meta Tags --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="meta_tags">
                            Meta Tags <span class="text-neutral-400 fw-normal text-sm">(comma separated)</span>
                        </label>
                        <input type="text"
                               class="form-control border border-neutral-200 radius-8"
                               id="meta_tags" name="meta_tags"
                               value="{{ old('meta_tags', $blog->meta_tags ?? '') }}"
                               placeholder="keyword1, keyword2, keyword3">
                    </div>

                    {{-- SEO Meta Description --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="meta_description">
                            Meta Description
                        </label>
                        <textarea class="form-control border border-neutral-200 radius-8" id="meta_description" name="meta_description" rows="3" placeholder="SEO meta description for search engines">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                    </div>

                    {{-- Description - Summernote --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900">
                            Post Description <span class="text-danger">*</span>
                        </label>
                        <textarea id="description" name="description"
                                  class="@error('description') is-invalid @enderror"
                        >{{ old('description', $blog->description ?? '') }}</textarea>
                        @error('description') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div>
                        <label class="form-label fw-bold text-neutral-900">Upload Thumbnail</label>
                        <div class="upload-image-wrapper">
                            <div class="uploaded-img {{ isset($blog) && $blog->thumbnail ? '' : 'd-none' }} position-relative h-160-px w-100 border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                <button type="button"
                                        class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex bg-danger-600 w-40-px h-40-px justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="radix-icons:cross-2" class="text-2xl text-white"></iconify-icon>
                                </button>
                                <img id="uploaded-img__preview"
                                     class="w-100 h-100 object-fit-cover"
                                     src="{{ isset($blog) && $blog->thumbnail ? asset('storage/' . $blog->thumbnail) : asset('assets/images/user.png') }}"
                                     alt="image">
                            </div>
                            <label class="upload-file h-160-px w-100 border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                   for="upload-file">
                                <iconify-icon icon="solar:camera-outline" class="text-xl text-secondary-light"></iconify-icon>
                                <span class="fw-semibold text-secondary-light">Upload</span>
                                <input id="upload-file" type="file" name="thumbnail" hidden accept="image/*">
                            </label>
                        </div>
                        @error('thumbnail') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-12">
                        <button type="submit" class="btn btn-primary-600 radius-8 px-32">
                            <i class="ri-save-line me-1"></i>
                            {{ isset($blog) ? 'Update Post' : 'Publish Post' }}
                        </button>
                        <a href="{{ route('blog') }}" class="btn btn-outline-secondary radius-8 px-24">Cancel</a>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-24">
            <div class="admin-section-card">
                <div class="admin-section-card__header">
                    <h6 class="text-xl mb-0">Latest Posts</h6>
                </div>
                <div class="admin-section-card__body d-flex flex-column gap-24">
                    @forelse ($blogs as $post)
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('blogDetails', $post->id) }}" class="blog__thumb w-100 radius-12 overflow-hidden">
                                <img src="{{ $post->thumbnail_url }}" alt="" class="w-100 h-100 object-fit-cover">
                            </a>
                            <div class="blog__content">
                                <h6 class="mb-8">
                                    <a href="{{ route('blogDetails', $post->id) }}"
                                       class="text-line-2 text-hover-primary-600 text-md transition-2">
                                        {{ $post->title }}
                                    </a>
                                </h6>
                                <p class="text-line-2 text-sm text-neutral-500 mb-0">{{ $post->category->name ?? '' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-neutral-400 text-sm mb-0">No posts yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
