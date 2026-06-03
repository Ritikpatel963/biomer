@extends('layout.frontlayout')

@section('title', 'Blog Insights - Bharat Biomer')
@section('seo_description', 'Explore the latest blog posts from Bharat Biomer on sustainable agriculture, biotech solutions, and farm innovation.')
@section('seo_keywords', 'Bharat Biomer blog, agriculture blog, biotech news, farming tips, sustainable agriculture')

@section('content')
 <!-- ========================
       SECTION 1: About Hero
  ======================== -->
  <section class="abth__section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-9 text-center">
          <h1 class="abth__heading">Blog</h1>
          <p class="abth__desc">Read the latest insights on sustainable agriculture, soil health, and biological farming innovations.</p>
        </div>
      </div>
    </div>
  </section>
  {{-- End Section --}}
<section class="py-5">
    <div class="container">
        <div class="row gx-4 gy-4">

            <div class="col-lg-8">
                <div class="row g-4">
                    @forelse ($blogs as $blog)
                        <div class="col-12">
                            <article class="card border-0 shadow-sm overflow-hidden">
                                <div class="row g-0 align-items-center">
                                    <div class="col-md-5">
                                        <a href="{{ route('frontend.blog.show', $blog->slug) }}">
                                            <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="img-fluid w-100 h-100 object-fit-cover">
                                        </a>
                                    </div>
                                    <div class="col-md-7 p-4">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 text-muted small">
                                            <span>{{ $blog->category->name ?? 'General' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $blog->reading_time ?? 5 }} min read</span>
                                        </div>
                                        <h2 class="h4 mb-2">
                                            <a href="{{ route('frontend.blog.show', $blog->slug) }}" class="text-dark text-decoration-none">
                                                {{ $blog->title }}
                                            </a>
                                        </h2>
                                        <p class="text-secondary mb-3">{{ Str::limit(strip_tags($blog->description), 140) }}</p>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <span class="badge rounded-pill bg-success-light text-success">{{ $blog->author ?? 'Bharat Biomer' }}</span>
                                            @if($blog->tags)
                                                @foreach(explode(',', $blog->tags) as $tag)
                                                    <span class="badge rounded-pill bg-secondary-light text-secondary">{{ trim($tag) }}</span>
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
            </div>

        </div>
    </div>
</section>
@endsection
