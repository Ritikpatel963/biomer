@extends('layout.layout')

@php
    $title = isset($blog) ? 'Edit Blog' : 'Add Blog';
    $subTitle = isset($blog) ? 'Edit Blog' : 'Add Blog';
    $script = <<<'SCRIPT'
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const fileInput            = document.getElementById("upload-file");
            const imagePreview         = document.getElementById("uploaded-img__preview");
            const uploadedImgContainer = document.querySelector(".uploaded-img");
            const removeButton         = document.querySelector(".uploaded-img__remove");
            const titleInput           = document.getElementById("title");
            const slugInput            = document.getElementById("slug");
            const faqContainer         = document.getElementById("faqItems");
            const addFaqButton         = document.getElementById("addFaqItem");
            const faqTemplate          = document.getElementById("faqRowTemplate");
            let slugWasEdited          = slugInput.value.trim() !== "";

            const makeSlug = (value) => value
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-+|-+$/g, "");

            titleInput.addEventListener("input", () => {
                if (slugInput.dataset.autoSlug === "true" && !slugWasEdited) {
                    slugInput.value = makeSlug(titleInput.value);
                }
            });

            slugInput.addEventListener("input", () => {
                slugInput.value = makeSlug(slugInput.value);
                slugWasEdited = slugInput.value !== "";
            });

            const reindexFaqRows = () => {
                faqContainer.querySelectorAll(".faq-item").forEach((row, index) => {
                    row.querySelector(".faq-item-number").textContent = `FAQ ${index + 1}`;
                    row.querySelectorAll("[data-faq-field]").forEach((field) => {
                        field.name = `faqs[${index}][${field.dataset.faqField}]`;
                    });
                });
            };

            addFaqButton.addEventListener("click", () => {
                faqContainer.appendChild(faqTemplate.content.cloneNode(true));
                reindexFaqRows();
            });

            faqContainer.addEventListener("click", (event) => {
                const removeButton = event.target.closest(".remove-faq-item");
                if (!removeButton) return;

                const rows = faqContainer.querySelectorAll(".faq-item");
                const row = removeButton.closest(".faq-item");

                if (rows.length === 1) {
                    row.querySelectorAll("[data-faq-field]").forEach((field) => field.value = "");
                } else {
                    row.remove();
                    reindexFaqRows();
                }
            });

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

        <div class="col-lg-12">
            <div class="admin-page-card">
                <div class="admin-page-card__header">
                    <div>
                        <span class="admin-page-card__eyebrow">Content Editor</span>
                        <!-- <h2 class="admin-page-card__title">{{ isset($blog) ? 'Edit Blog Post' : 'Add Blog Post' }}</h2>
                        <p class="admin-page-card__desc">Use the same admin card layout while editing content, SEO fields, status, and thumbnail assets.</p> -->
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
                            method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-20">
                            @csrf
                            <ul class="nav nav-tabs mb-4" id="blogTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-semibold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General Info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-semibold" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane" type="button" role="tab" aria-controls="content-pane" aria-selected="false">Content</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-semibold" id="author-tab" data-bs-toggle="tab" data-bs-target="#author-pane" type="button" role="tab" aria-controls="author-pane" aria-selected="false">Author & Publishing</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-semibold" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab" aria-controls="seo-pane" aria-selected="false">SEO & FAQ</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="blogTabContent">
                                {{-- General Info Tab --}}
                                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                    <div class="d-flex flex-column gap-20">
{{-- Title --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="title">
                                    Post Title <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control border border-neutral-200 radius-8 @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $blog->title ?? '') }}" maxlength="255"
                                    placeholder="Enter Post Title" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- SEO-friendly URL slug --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="slug">
                                    SEO-Friendly URL (Slug) <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control border border-neutral-200 radius-8 @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug', $blog->slug ?? '') }}"
                                    data-auto-slug="{{ isset($blog) ? 'false' : 'true' }}" maxlength="255"
                                    pattern="[a-z0-9]+(?:-[a-z0-9]+)*" autocomplete="off" required
                                    placeholder="organic-fertilizer-for-plants" aria-describedby="slugHelp">
                                <div id="slugHelp" class="form-text text-neutral-500">
                                    Enter a unique, search engine optimized URL slug for this page. Use lowercase letters,
                                    numbers, and hyphens (-) only. Avoid spaces and special characters to create clean,
                                    readable URLs that improve SEO and user experience.
                                    <span class="d-block mt-1">Example:
                                        <strong>organic-fertilizer-for-plants</strong></span>
                                </div>
                                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="category_id">
                                    Post Category <span class="text-danger">*</span>
                                </label>
                                <select
                                    class="form-select border border-neutral-200 radius-8 @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
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
                                <select
                                    class="form-select border border-neutral-200 radius-8 @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                    <option value="draft" {{ old('status', $blog->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $blog->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Tags --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="tags">
                                    Tags <span class="text-neutral-400 fw-normal text-sm">(comma separated)</span>
                                </label>
                                <input type="text" class="form-control border border-neutral-200 radius-8" id="tags"
                                    name="tags" value="{{ old('tags', $blog->tags ?? '') }}" maxlength="255"
                                    placeholder="e.g. technology, business, design">
                            </div>

                            
                                    </div>
                                </div>

                                {{-- Content Tab --}}
                                <div class="tab-pane fade" id="content-pane" role="tabpanel" aria-labelledby="content-tab">
                                    <div class="d-flex flex-column gap-20">
{{-- Description - Summernote --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900">
                                    Post Description <span class="text-danger">*</span>
                                </label>
                                <textarea id="description" name="description" required
                                    class="tinymce-editor @error('description') is-invalid @enderror">{{ old('description', $blog->description ?? '') }}</textarea>
                                @error('description') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Thumbnail --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900">Upload Thumbnail</label>
                                <div class="upload-image-wrapper">
                                    <div
                                        class="uploaded-img {{ isset($blog) && $blog->thumbnail ? '' : 'd-none' }} position-relative h-160-px w-100 border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                        <button type="button"
                                            class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex bg-danger-600 w-40-px h-40-px justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="radix-icons:cross-2"
                                                class="text-2xl text-white"></iconify-icon>
                                        </button>
                                        <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                            src="{{ isset($blog) && $blog->thumbnail ? asset('storage/' . $blog->thumbnail) : asset('assets/images/user.png') }}"
                                            alt="{{ isset($blog) ? $blog->thumbnail_alt_text : 'Thumbnail preview' }}">
                                    </div>
                                    <label
                                        class="upload-file h-160-px w-100 border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                        for="upload-file">
                                        <iconify-icon icon="solar:camera-outline"
                                            class="text-xl text-secondary-light"></iconify-icon>
                                        <span class="fw-semibold text-secondary-light">Upload</span>
                                        <input id="upload-file" type="file" name="thumbnail" hidden
                                            accept="image/jpeg,image/png,image/webp">
                                    </label>
                                </div>
                                @error('thumbnail') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Thumbnail alt text --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="thumbnail_alt">
                                    Image Alt Text
                                </label>
                                <input type="text"
                                    class="form-control border border-neutral-200 radius-8 @error('thumbnail_alt') is-invalid @enderror"
                                    id="thumbnail_alt" name="thumbnail_alt"
                                    value="{{ old('thumbnail_alt', $blog->thumbnail_alt ?? '') }}" maxlength="255"
                                    placeholder="Describe the blog thumbnail image" aria-describedby="thumbnailAltHelp">
                                <div id="thumbnailAltHelp" class="form-text text-neutral-500">
                                    Briefly describe the image for search engines and visitors using screen readers. Keep it
                                    specific and avoid keyword stuffing.
                                </div>
                                @error('thumbnail_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>


                                    </div>
                                </div>

                                {{-- Author & Publishing Tab --}}
                                <div class="tab-pane fade" id="author-pane" role="tabpanel" aria-labelledby="author-tab">
                                    <div class="d-flex flex-column gap-20">
{{-- Author --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="author">
                                    Author
                                </label>
                                <input type="text" class="form-control border border-neutral-200 radius-8" id="author"
                                    name="author" value="{{ old('author', $blog->author ?? '') }}" maxlength="255"
                                    placeholder="Enter author name">
                            </div>

                            {{-- Author Bio --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="author_bio">
                                    Author Bio
                                </label>
                                <textarea
                                    class="form-control border border-neutral-200 radius-8 @error('author_bio') is-invalid @enderror"
                                    id="author_bio" name="author_bio" rows="3" maxlength="2000"
                                    placeholder="Brief author biography, experience, or expertise">{{ old('author_bio', $blog->author_bio ?? '') }}</textarea>
                                @error('author_bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Reading Time --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="reading_time">
                                    Reading Time (minutes)
                                </label>
                                <input type="number" class="form-control border border-neutral-200 radius-8"
                                    id="reading_time" name="reading_time"
                                    value="{{ old('reading_time', $blog->reading_time ?? 5) }}" min="1" max="120">
                            </div>

                            {{-- Publish Date --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="published_at">
                                    Publish Date
                                </label>
                                <input type="datetime-local"
                                    class="form-control border border-neutral-200 radius-8 @error('published_at') is-invalid @enderror"
                                    id="published_at" name="published_at"
                                    value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\\TH:i') : '') }}">
                                <div class="form-text text-neutral-500">Leave blank to use the time when the post is first
                                    published. The last updated date is maintained automatically.</div>
                                @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            
                                    </div>
                                </div>

                                {{-- SEO & FAQ Tab --}}
                                <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="d-flex flex-column gap-20">
{{-- SEO Meta Title --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="meta_title">
                                    Meta Title
                                </label>
                                <input type="text"
                                    class="form-control border border-neutral-200 radius-8 @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title"
                                    value="{{ old('meta_title', $blog->meta_title ?? '') }}" maxlength="255"
                                    placeholder="SEO meta title for this blog post" aria-describedby="metaTitleHelp">
                                <div id="metaTitleHelp" class="form-text text-neutral-500">
                                    Write a clear, relevant title for search engines that includes the page's primary topic.
                                    Recommended length: 50–60 characters.
                                </div>
                                @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Canonical URL --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="canonical_url">
                                    Canonical Tag URL
                                </label>
                                <input type="url"
                                    class="form-control border border-neutral-200 radius-8 @error('canonical_url') is-invalid @enderror"
                                    id="canonical_url" name="canonical_url"
                                    value="{{ old('canonical_url', $blog->canonical_url ?? '') }}" maxlength="2048"
                                    placeholder="https://example.com/blogs/organic-fertilizer-for-plants"
                                    aria-describedby="canonicalUrlHelp">
                                <div id="canonicalUrlHelp" class="form-text text-neutral-500">
                                    Optionally enter the preferred absolute URL for this post. Leave blank to automatically
                                    use its public blog URL.
                                </div>
                                @error('canonical_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- SEO Meta Tags --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="meta_tags">
                                    Meta Tags <span class="text-neutral-400 fw-normal text-sm">(comma separated)</span>
                                </label>
                                <input type="text" class="form-control border border-neutral-200 radius-8" id="meta_tags"
                                    name="meta_tags" value="{{ old('meta_tags', $blog->meta_tags ?? '') }}" maxlength="255"
                                    placeholder="keyword1, keyword2, keyword3">
                            </div>

                            {{-- SEO Meta Description --}}
                            <div>
                                <label class="form-label fw-bold text-neutral-900" for="meta_description">
                                    Meta Description
                                </label>
                                <textarea
                                    class="form-control border border-neutral-200 radius-8 @error('meta_description') is-invalid @enderror"
                                    id="meta_description" name="meta_description" rows="3" maxlength="500"
                                    placeholder="SEO meta description for search engines"
                                    aria-describedby="metaDescriptionHelp">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                                <div id="metaDescriptionHelp" class="form-text text-neutral-500">
                                    Write a concise and compelling description for search engines. This summary helps users
                                    understand your content and can improve click-through rates. Recommended length: 150–160
                                    characters.
                                </div>
                                @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- FAQ content and schema --}}
                            @php
                                $faqRows = old('faqs');
                                if ($faqRows === null) {
                                    $faqRows = isset($blog) && !empty($blog->faq_items)
                                        ? $blog->faq_items
                                        : [['question' => '', 'answer' => '']];
                                }
                            @endphp
                            <div>
                                <div class="d-flex align-items-center justify-content-between gap-3 mb-12">
                                    <div>
                                        <label class="form-label fw-bold text-neutral-900 mb-1">Frequently Asked
                                            Questions</label>
                                        <div class="form-text text-neutral-500 mt-0">Add up to 20 questions that will appear
                                            on the blog page and in its FAQ structured data.</div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm flex-shrink-0"
                                        id="addFaqItem">
                                        <i class="ri-add-line me-1"></i>Add FAQ
                                    </button>
                                </div>

                                <div id="faqItems" class="d-flex flex-column gap-12">
                                    @foreach($faqRows as $index => $faq)
                                        <div class="faq-item border border-neutral-200 radius-8 p-16">
                                            <div class="d-flex align-items-center justify-content-between mb-12">
                                                <span class="faq-item-number fw-semibold text-neutral-700">FAQ
                                                    {{ $loop->iteration }}</span>
                                                <button type="button" class="remove-faq-item btn btn-sm btn-outline-danger"
                                                    aria-label="Remove FAQ">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                            <div class="mb-12">
                                                <label class="form-label" for="faq_question_{{ $index }}">Question</label>
                                                <input type="text"
                                                    class="form-control @error("faqs.$index.question") is-invalid @enderror"
                                                    id="faq_question_{{ $index }}" data-faq-field="question"
                                                    name="faqs[{{ $index }}][question]" value="{{ $faq['question'] ?? '' }}"
                                                    maxlength="500" placeholder="Enter a frequently asked question">
                                                @error("faqs.$index.question") <div class="invalid-feedback">{{ $message }}
                                                </div> @enderror
                                            </div>
                                            <div>
                                                <label class="form-label" for="faq_answer_{{ $index }}">Answer</label>
                                                <textarea class="form-control @error("faqs.$index.answer") is-invalid @enderror"
                                                    id="faq_answer_{{ $index }}" data-faq-field="answer"
                                                    name="faqs[{{ $index }}][answer]" rows="3" maxlength="5000"
                                                    placeholder="Enter a clear and helpful answer">{{ $faq['answer'] ?? '' }}</textarea>
                                                @error("faqs.$index.answer") <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <template id="faqRowTemplate">
                                    <div class="faq-item border border-neutral-200 radius-8 p-16">
                                        <div class="d-flex align-items-center justify-content-between mb-12">
                                            <span class="faq-item-number fw-semibold text-neutral-700">FAQ</span>
                                            <button type="button" class="remove-faq-item btn btn-sm btn-outline-danger"
                                                aria-label="Remove FAQ">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                        <div class="mb-12">
                                            <label class="form-label">Question</label>
                                            <input type="text" class="form-control" data-faq-field="question"
                                                maxlength="500" placeholder="Enter a frequently asked question">
                                        </div>
                                        <div>
                                            <label class="form-label">Answer</label>
                                            <textarea class="form-control" data-faq-field="answer" rows="3" maxlength="5000"
                                                placeholder="Enter a clear and helpful answer"></textarea>
                                        </div>
                                    </div>
                                </template>

                                @error('faqs') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="d-flex gap-12 mt-4 pt-4 border-top border-neutral-200">
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


    </div>

@endsection