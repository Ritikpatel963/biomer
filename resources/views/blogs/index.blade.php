@extends('layout.frontlayout')

@section('body_class', 'no-product-motion')

@section('title', $pageSeo?->meta_title ?: 'Blog Insights - Bharat Biomer')
@section('seo_description', $pageSeo?->meta_description ?: 'Explore the latest blog posts from Bharat Biomer on sustainable agriculture, biotech solutions, and farm innovation.')
@section('seo_keywords', $pageSeo?->meta_keyword ?: 'Bharat Biomer blog, agriculture blog, biotech news, farming tips, sustainable agriculture')

@section('content')
 <!-- ========================
       SECTION 1: About Hero
  ======================== -->
  <x-front-breadcrumb
    badge="Blog"
    title="Blog"
    description="Read the latest insights on sustainable agriculture, soil health, and biological farming innovations."
    align="center"
  />
  {{-- End Section --}}
<section class="py-5 blog-index-page">
    <div class="container">
        <div class="row gx-4 gy-4">

            <div class="col-lg-8">
                <div class="row g-3">
                    @forelse ($blogs as $blog)
                        <div class="col-12">
                            <article class="card overflow-hidden blog-list-card">
                                <div class="row g-0 align-items-stretch blog-list-card__row">
                                    <div class="col-md-5 blog-list-card__media">
                                        <a href="{{ route('frontend.blog.show', $blog->slug) }}" class="blog-list-card__media-link">
                                            <img
                                                src="{{ $blog->thumbnail_url }}"
                                                alt="{{ $blog->thumbnail_alt_text }}"
                                                class="blog-list-card__image"
                                                width="640"
                                                height="420"
                                                decoding="async"
                                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                @if($loop->first) fetchpriority="high" @endif
                                            >
                                        </a>
                                    </div>
                                    <div class="col-md-7 p-4 d-flex flex-column justify-content-center">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 text-muted small">
                                            <span>{{ $blog->category->name ?? 'General' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ ($blog->published_at ?: $blog->created_at)->format('M d, Y') }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $blog->reading_time ?? 5 }} min read</span>
                                        </div>
                                        <h2 class="h4 mb-2">
                                            <a href="{{ route('frontend.blog.show', $blog->slug) }}" class="text-dark text-decoration-none">
                                                {{ $blog->title }}
                                            </a>
                                        </h2>
                                        <p class="text-secondary mb-3 blog-list-card__excerpt">{!! \App\Services\BlogExcerpt::render($blog->description, 140) !!}</p>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <span class="blog-meta-pill blog-meta-pill--author">{{ $blog->author ?? 'Bharat Biomer' }}</span>
                                            @if($blog->tags)
                                                @foreach(explode(',', $blog->tags) as $tag)
                                                    <span class="blog-meta-pill blog-meta-pill--tag">{{ trim($tag) }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning">No blog posts are available yet. Please check back soon.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $blogs->links() }}
                </div>
            </div>

            <div class="col-lg-4">
                <div class="border rounded-4 p-4 bg-white shadow-sm">
                    <h4 class="h5 mb-3">Categories</h4>
                    <ul class="list-unstyled mb-0">
                        @foreach($categories as $category)
                            <li class="py-2 border-bottom">
                                {{ $category->name }} <span class="text-muted">({{ $category->blogs_count }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border rounded-4 p-4 bg-white shadow-sm mt-4">
                    <h4 class="h5 mb-3">Popular Tags</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($tags as $tag)
                            <span class="badge rounded-pill bg-primary-light text-primary">{{ $tag }}</span>
                        @empty
                            <span class="text-muted">No tags available.</span>
                        @endforelse
                    </div>
                </div>

                <div class="border rounded-4 p-4 bg-white shadow-sm mt-4">
                    <h4 class="h5 mb-3">Recent Posts</h4>
                    <div class="recent-posts-list">
                        @forelse($recentBlogs as $recentBlog)
                            <a href="{{ route('frontend.blog.show', $recentBlog->slug) }}" class="recent-post-item">
                                <img src="{{ $recentBlog->thumbnail_url }}"
                                     alt="{{ $recentBlog->thumbnail_alt_text }}"
                                     class="recent-post-item__image"
                                     width="72" height="72" loading="lazy" decoding="async">
                                <span class="recent-post-item__content">
                                    <span class="recent-post-item__title">{{ $recentBlog->title }}</span>
                                    <span class="recent-post-item__date">{{ ($recentBlog->published_at ?: $recentBlog->created_at)->format('M d, Y') }}</span>
                                </span>
                            </a>
                        @empty
                            <span class="text-muted">No recent posts available.</span>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
