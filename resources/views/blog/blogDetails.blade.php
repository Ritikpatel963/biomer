@extends('layout.layout')

@php
    $title    = $blog->title;
    $subTitle = 'Blog Details';
@endphp

@section('content')

<div class="row gy-4">

    {{-- ── Main Content ─────────────────────────────────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Blog Card --}}
        <div class="card p-0 radius-12 overflow-hidden">
            <div class="card-body p-0">

                {{-- Thumbnail --}}
                @if($blog->thumbnail)
                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}"
                         class="w-100 object-fit-cover" style="height:380px;">
                @endif

                <div class="p-32">

                    {{-- Meta --}}
                    <div class="d-flex align-items-center gap-16 justify-content-between flex-wrap mb-24">
                        <div class="d-flex align-items-center gap-8">
                            <div class="w-48-px h-48-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center">
                                <i class="ri-user-line text-primary-600 text-xl"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="text-lg mb-0">Admin</h6>
                                <span class="text-sm text-neutral-500">{{ $blog->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-md-3 gap-2 flex-wrap">
                            <div class="d-flex align-items-center gap-8 text-neutral-500 text-lg fw-medium">
                                <i class="ri-folder-line"></i>
                                {{ $blog->category->name ?? '—' }}
                            </div>
                            <div class="d-flex align-items-center gap-8 text-neutral-500 text-lg fw-medium">
                                <i class="ri-calendar-2-line"></i>
                                {{ $blog->created_at->format('M d, Y') }}
                            </div>
                            <div class="d-flex align-items-center gap-8">
                                @if($blog->status === 'published')
                                    <span class="badge bg-success-100 text-success-700 px-12 py-6 radius-8">Published</span>
                                @else
                                    <span class="badge bg-warning-100 text-warning-700 px-12 py-6 radius-8">Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Title --}}
                    <h3 class="mb-16">{{ $blog->title }}</h3>

                    {{-- Tags --}}
                    @if($blog->tags)
                        <div class="d-flex align-items-center flex-wrap gap-8 mb-20">
                            @foreach(explode(',', $blog->tags) as $tag)
                                <span class="btn btn-sm bg-primary-50 text-primary-600 border-0 px-16 py-6 radius-8 text-sm">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="text-neutral-500 blog-content">
                        {!! \App\Services\HtmlSanitizer::clean($blog->description) !!}
                    </div>

                    <div class="d-flex align-items-center gap-12 flex-wrap mt-24 pt-24 border-top border-dashed">
                        <span class="text-neutral-500 fw-medium">Share</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('frontend.blog.show', $blog->slug)) }}" target="_blank" class="btn btn-sm bg-primary-50 text-primary-600 border-0 px-16 py-6 radius-8">
                            <i class="ri-facebook-fill"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('frontend.blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="btn btn-sm bg-primary-50 text-primary-600 border-0 px-16 py-6 radius-8">
                            <i class="ri-twitter-x-line"></i> X
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . route('frontend.blog.show', $blog->slug)) }}" target="_blank" class="btn btn-sm bg-primary-50 text-primary-600 border-0 px-16 py-6 radius-8">
                            <i class="ri-whatsapp-line"></i> WhatsApp
                        </a>
                    </div>

                    {{-- Edit/Delete actions --}}
                    <div class="d-flex align-items-center gap-12 mt-32 pt-24 border-top border-dashed">
                        <a href="{{ route('editBlog', $blog->id) }}"
                           class="btn btn-primary-600 radius-8 d-flex align-items-center gap-2">
                            <i class="ri-edit-2-line"></i> Edit Post
                        </a>
                        <form action="{{ route('destroyBlog', $blog->id) }}" method="POST"
                              onsubmit="return confirm('Delete this post?')">
                            @csrf
                            <button type="submit" class="btn btn-danger-600 radius-8 d-flex align-items-center gap-2">
                                <i class="ri-delete-bin-6-line"></i> Delete
                            </button>
                        </form>
                        <a href="{{ route('blog') }}" class="btn btn-outline-secondary radius-8 d-flex align-items-center gap-2">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- ── Sidebar ──────────────────────────────────────────────────────────── --}}
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-24">

            {{-- Latest Posts --}}
            <div class="card">
                <div class="card-header border-bottom">
                    <h6 class="text-xl mb-0">Latest Posts</h6>
                </div>
                <div class="card-body d-flex flex-column gap-24 p-24">
                    @forelse ($recentBlogs as $post)
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
                                <p class="text-line-2 text-sm text-neutral-500 mb-0">
                                    {{ $post->category->name ?? '' }} &bull; {{ $post->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-neutral-400 text-sm mb-0">No other posts yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Categories --}}
            <div class="card">
                <div class="card-header border-bottom">
                    <h6 class="text-xl mb-0">Categories</h6>
                </div>
                <div class="card-body p-24">
                    <ul class="list-unstyled mb-0">
                        @foreach($categories as $cat)
                            <li class="w-100 d-flex align-items-center justify-content-between flex-wrap gap-8
                                       {{ !$loop->last ? 'border-bottom border-dashed pb-12 mb-12' : '' }}">
                                <a href="{{ route('blog') }}" class="text-hover-primary-600 transition-2">
                                    {{ $cat->name }}
                                </a>
                                <span class="text-neutral-500 w-28-px h-28-px rounded-circle bg-neutral-100 d-flex justify-content-center align-items-center transition-2 text-xs fw-semibold">
                                    {{ str_pad($cat->blogs_count, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Tags --}}
            @if($blog->tags)
                <div class="card">
                    <div class="card-header border-bottom">
                        <h6 class="text-xl mb-0">Tags</h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-8">
                            @foreach(explode(',', $blog->tags) as $tag)
                                <span class="btn btn-sm bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 text-sm px-16 py-6">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection
