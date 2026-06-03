@extends('layout.frontlayout')
@section('title', 'Bharat Biomer – Nature-Powered Biology')
@push('styles')
<style>
    .slider-wrapper {
      --slide-duration: 850ms;
      --ease: cubic-bezier(0.16, 1, 0.3, 1);
      width: 100%;
      max-width: none;
      padding: 0;
      margin: 0;
      position: relative;
    }

    .slider {
      position: relative;
      width: 100%;
      height: 100vh;
      height: 90dvh;
      border-radius: 0;
      overflow: hidden;
      box-shadow: none;
    }

    .slide {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      overflow: hidden;
      transform: translateX(100%);
      transition: transform var(--slide-duration) var(--ease);
      z-index: 1;
      cursor: pointer;
    }

    .slide.active {
      transform: translateX(0%);
      z-index: 3;
    }

    .slide.exit-left {
      transform: translateX(-100%);
      z-index: 2;
    }

    .slide.exit-right {
      transform: translateX(100%);
      z-index: 2;
    }

    .slide.from-left {
      transform: translateX(-100%);
    }

    .slide-image {
      position: absolute;
      inset: 0;
      height: 100%;
      width: 100%;
      object-fit: cover;
      object-position: center;
      z-index: 0;
      pointer-events: none;
    }

    .controls {
      position: absolute;
      right: 16px;
      bottom: 14px;
      z-index: 7;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 0;
      padding: 12px 14px;
      border-radius: 999px;
    }

    .nav-arrows {
      display: flex;
      gap: 10px;
    }

    .nav-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      border: 1.5px solid rgba(255,255,255,0.15);
      background: rgb(25 78 32 / 97%);
      color: rgb(255 255 255);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .nav-btn:hover {
      border-color: rgba(255,255,255,0.4);
      background: rgba(255,255,255,0.12);
      color: #fff;
      transform: scale(1.08);
    }

    .nav-btn:active {
      transform: scale(0.95);
    }

    @media (max-width: 900px) {
      .slider { height: 100vh; height: 100dvh; }
      .controls { left: 12px; right: 12px; }
    }

    @media (max-width: 640px) {
      .slider { height: 100vh; height: 100dvh; border-radius: 0; }
      .controls { left: 10px; right: 10px; padding: 10px 12px; }
    }

    @media (max-width: 420px) {
      .slider { height: 100vh; height: 100dvh; }
      .controls { left: 8px; right: 8px; }
    }
</style>
@endpush



@php
  $siteSettings = \App\Models\SiteSetting::first();
  $homepageConfig = \App\Models\HomepageSetting::currentMerged();
  $homeBannerImages = collect([
    $siteSettings?->home_banner_image_1,
    $siteSettings?->home_banner_image_2,
    $siteSettings?->home_banner_image_3,
    $siteSettings?->home_banner_image_4,
  ])->filter(function ($path) {
    return filled($path);
  })->map(function ($path) {
    if (blank($path)) {
      return null;
    }

    $path = trim((string) $path);

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
      return $path;
    }

    $normalizedPath = ltrim($path, '/');
    if (str_starts_with($normalizedPath, 'storage/')) {
      $publicRelativePath = $normalizedPath;
    } elseif (str_starts_with($normalizedPath, 'public/')) {
      $publicRelativePath = 'storage/' . ltrim(substr($normalizedPath, 7), '/');
    } else {
      $publicRelativePath = 'storage/' . $normalizedPath;
    }

    $url = asset($publicRelativePath);
    $absolutePath = public_path($publicRelativePath);
    if (is_file($absolutePath)) {
      $url .= '?v=' . filemtime($absolutePath);
    }

    return $url;
  })->filter()->values();

  $heroSlides = collect($homepageConfig['hero_slides'])
    ->filter(fn($slide) => !empty($slide['is_active']))
    ->map(function ($slide) {
      $mediaUrl = trim((string) ($slide['media_url'] ?? ''));
      if ($mediaUrl !== '' && !str_starts_with($mediaUrl, 'http://') && !str_starts_with($mediaUrl, 'https://')) {
        $mediaUrl = asset('storage/' . ltrim($mediaUrl, '/'));
      }
      $slide['media_url'] = $mediaUrl;
      return $slide;
    })
    ->values();
  if ($heroSlides->isEmpty()) {
    $heroSlides = collect(\App\Models\HomepageSetting::defaults()['hero_slides']);
  }
  $videoReviews = collect($homepageConfig['video_reviews_items'])->take(6)->values();

  $embedMediaUrl = function ($url) {
    $url = trim((string) $url);
    if ($url === '') {
      return null;
    }

    if (str_contains($url, 'youtube.com/watch?v=')) {
      parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $queryParams);
      $videoId = $queryParams['v'] ?? null;
      return $videoId ? 'https://www.youtube.com/embed/' . $videoId : null;
    }

    if (str_contains($url, 'youtu.be/')) {
      $videoId = trim((string) basename(parse_url($url, PHP_URL_PATH) ?? ''));
      return $videoId ? 'https://www.youtube.com/embed/' . $videoId : null;
    }

    if (str_contains($url, 'vimeo.com/')) {
      $videoId = trim((string) basename(parse_url($url, PHP_URL_PATH) ?? ''));
      return $videoId ? 'https://player.vimeo.com/video/' . $videoId : null;
    }

    return null;
  };

  $whyItems = $homepageConfig['why_bharat_items'];
  $whatWeDoItems = $homepageConfig['what_we_do_items'];
  $whoWeServeItems = $homepageConfig['who_we_serve_items'];
  $highlightItems = $homepageConfig['key_highlights_items'];
@endphp

@section('content')
  {{-- banner start --}}
@if($homeBannerImages->isNotEmpty())
<div class="slider-wrapper">
  <div class="slider" id="slider">
    @foreach($homeBannerImages as $index => $bannerImage)
      <div class="slide {{ $index === 0 ? 'active' : '' }}">
        <img class="slide-image" src="{{ $bannerImage }}" alt="Banner slide {{ $index + 1 }}"/>
      </div>
    @endforeach
  </div>

  @if($homeBannerImages->count() > 1)
    <div class="controls">
      <div class="nav-arrows">
        <button class="nav-btn" id="prevBtn" aria-label="Previous">&#8592;</button>
        <button class="nav-btn" id="nextBtn" aria-label="Next">&#8594;</button>
      </div>
    </div>
  @endif
</div>
@endif
  {{-- banner end --}}
  <!-- ═══════════════════════════
       HERO SECTION
  ═══════════════════════════ -->
  @if($homepageConfig['hero_enabled'])
    <section class="bb-hero-section">
      <div class="container">
        @if($homepageConfig['hero_slider_enabled'] && $heroSlides->count() > 1)
          <div class="swiper bb-hero-swiper">
            <div class="swiper-wrapper">
              @foreach($heroSlides as $index => $slide)
                <div class="swiper-slide">
                  <div class="row align-items-center g-5">
                    <div class="col-lg-6 bb-hero-text-col">
                      <h1 class="bb-hero-headline">
                        {{ $slide['headline_prefix'] }}<br>
                        <span>{{ $slide['headline_highlight'] }}</span> {{ $slide['headline_suffix'] }}
                      </h1>
                      <p class="bb-hero-desc">{{ $slide['description'] }}</p>
                      @if($slide['secondary_description'])
                        <p class="bb-hero-desc">{{ $slide['secondary_description'] }}</p>
                      @endif
                      <div class="bb-cta-group">
                        @if($slide['primary_button_text'] && $slide['primary_button_url'])
                          <a href="{{ $slide['primary_button_url'] }}"
                            class="bb-btn-primary-cta">{{ $slide['primary_button_text'] }}</a>
                        @endif
                        @if($slide['secondary_button_text'] && $slide['secondary_button_url'])
                          <a href="{{ $slide['secondary_button_url'] }}"
                            class="bb-btn-outline-cta">{{ $slide['secondary_button_text'] }}</a>
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-6 bb-hero-img-col">
                      <div class="position-relative">
                        <div class="bb-dot-grid"></div>
                        <div class="bb-hero-img-wrapper">
                          @php $slideEmbedUrl = $embedMediaUrl($slide['media_url']); @endphp
                          @if($slide['media_type'] === 'video' && $slideEmbedUrl)
                            <div class="ratio ratio-16x9 bb-hero-video-frame">
                              <iframe src="{{ $slideEmbedUrl }}" title="Hero video {{ $index + 1 }}" allowfullscreen
                                loading="lazy"></iframe>
                            </div>
                          @elseif($slide['media_type'] === 'video')
                            <video controls playsinline preload="metadata" class="bb-hero-video">
                              <source src="{{ $slide['media_url'] }}">
                            </video>
                          @else
                            <img src="{{ $slide['media_url'] }}" alt="Homepage hero media {{ $index + 1 }}" loading="lazy" />
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="bb-hero-swiper__nav">
              <button type="button" class="bb-hero-swiper__button bb-hero-swiper__button--prev" aria-label="Previous slide">
                <i class="ri-arrow-left-s-line"></i>
              </button>
              <div class="swiper-pagination bb-hero-swiper__pagination"></div>
              <button type="button" class="bb-hero-swiper__button bb-hero-swiper__button--next" aria-label="Next slide">
                <i class="ri-arrow-right-s-line"></i>
              </button>
            </div>
          </div>
        @else
          @php $slide = $heroSlides->first();
          $slideEmbedUrl = $embedMediaUrl($slide['media_url'] ?? ''); @endphp
          <div class="row align-items-center g-5">
            <div class="col-lg-6 bb-hero-text-col">
              <h1 class="bb-hero-headline">
                {{ $slide['headline_prefix'] }}<br>
                <span>{{ $slide['headline_highlight'] }}</span> {{ $slide['headline_suffix'] }}
              </h1>
              <p class="bb-hero-desc">{{ $slide['description'] }}</p>
              @if(!empty($slide['secondary_description']))
                <p class="bb-hero-desc">{{ $slide['secondary_description'] }}</p>
              @endif
              <div class="bb-cta-group">
                @if(!empty($slide['primary_button_text']) && !empty($slide['primary_button_url']))
                  <a href="{{ $slide['primary_button_url'] }}"
                    class="bb-btn-primary-cta">{{ $slide['primary_button_text'] }}</a>
                @endif
                @if(!empty($slide['secondary_button_text']) && !empty($slide['secondary_button_url']))
                  <a href="{{ $slide['secondary_button_url'] }}"
                    class="bb-btn-outline-cta">{{ $slide['secondary_button_text'] }}</a>
                @endif
              </div>
            </div>
            <div class="col-lg-6 bb-hero-img-col">
              <div class="position-relative">
                <div class="bb-dot-grid"></div>
                <div class="bb-hero-img-wrapper">
                  @if(($slide['media_type'] ?? 'image') === 'video' && $slideEmbedUrl)
                    <div class="ratio ratio-16x9 bb-hero-video-frame">
                      <iframe src="{{ $slideEmbedUrl }}" title="Homepage hero video" allowfullscreen loading="lazy"></iframe>
                    </div>
                  @elseif(($slide['media_type'] ?? 'image') === 'video')
                    <video controls playsinline preload="metadata" class="bb-hero-video">
                      <source src="{{ $slide['media_url'] ?? '' }}">
                    </video>
                  @else
                    <img src="{{ $slide['media_url'] ?? '' }}" alt="Hands holding rich soil with a green seedling"
                      loading="lazy" />
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </section>
  @endif
  <!-- ═══════════════════════════
         SECTION 2: WHY BHARAT BIOMER
    ═══════════════════════════ -->
  <section class="bb-section-wrapper">
    <div class="container">

      <div class="text-center">
        <h2 class="bb-section-heading">{{ $homepageConfig['why_bharat_title'] }}</h2>
        <span class="bb-heading-underline"></span>
      </div>

      <div class="swiper bb-why-swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="bb-feature-card">
              <div class="bb-icon-badge">
                <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M11.25 0H6.25H5C4.30859 0 3.75 0.558594 3.75 1.25C3.75 1.94141 4.30859 2.5 5 2.5V7.6875C5 8.14844 4.87109 8.60547 4.62891 8.99609L0.402344 15.8672C0.140625 16.2969 0 16.7852 0 17.2891C0 18.7852 1.21484 20 2.71094 20H14.7891C16.2852 20 17.5 18.7852 17.5 17.2891C17.5 16.7891 17.3594 16.2969 17.0977 15.8672L12.8711 9C12.6289 8.60547 12.5 8.15234 12.5 7.69141V2.5C13.1914 2.5 13.75 1.94141 13.75 1.25C13.75 0.558594 13.1914 0 12.5 0H11.25ZM7.5 7.6875V2.5H10V7.6875C10 8.61328 10.2578 9.51953 10.7422 10.3086L12.0898 12.5H5.41016L6.75781 10.3086C7.24219 9.51953 7.5 8.61328 7.5 7.6875Z"
                    fill="white" />
                </svg>
              </div>
              <h3 class="bb-card-title">{{ $whyItems[0]['title'] }}</h3>
              <p class="bb-card-desc">{{ $whyItems[0]['description'] }}</p>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="bb-feature-card">
              <div class="bb-icon-badge">
                <svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M15.9375 4.6875C15.9375 6.82031 13.082 10.6211 11.8281 12.1875C11.5273 12.5625 10.9688 12.5625 10.6719 12.1875C9.41797 10.6211 6.5625 6.82031 6.5625 4.6875C6.5625 2.09766 8.66016 0 11.25 0C13.8398 0 15.9375 2.09766 15.9375 4.6875ZM16.25 7.82812C16.3867 7.55859 16.5117 7.28906 16.625 7.02344C16.6445 6.97656 16.6641 6.92578 16.6836 6.87891L21.2148 5.06641C21.832 4.82031 22.5 5.27344 22.5 5.9375V16.5156C22.5 16.8984 22.2656 17.2422 21.9102 17.3867L16.25 19.6484V7.82812ZM5.375 5.40234C5.46875 5.95312 5.65625 6.50781 5.875 7.02344C5.98828 7.28906 6.11328 7.55859 6.25 7.82812V17.6484L1.28516 19.6367C0.667969 19.8828 0 19.4297 0 18.7656V8.1875C0 7.80469 0.234375 7.46094 0.589844 7.31641L5.37891 5.40234H5.375ZM12.8047 12.9688C13.3477 12.2891 14.1992 11.1836 15 9.96094V19.6992L7.5 17.5547V9.96094C8.30078 11.1836 9.15234 12.2891 9.69531 12.9688C10.4961 13.9688 12.0039 13.9688 12.8047 12.9688ZM11.25 5.9375C11.6644 5.9375 12.0618 5.77288 12.3549 5.47985C12.6479 5.18683 12.8125 4.7894 12.8125 4.375C12.8125 3.9606 12.6479 3.56317 12.3549 3.27015C12.0618 2.97712 11.6644 2.8125 11.25 2.8125C10.8356 2.8125 10.4382 2.97712 10.1451 3.27015C9.85212 3.56317 9.6875 3.9606 9.6875 4.375C9.6875 4.7894 9.85212 5.18683 10.1451 5.47985C10.4382 5.77288 10.8356 5.9375 11.25 5.9375Z"
                    fill="white" />
                </svg>
              </div>
              <h3 class="bb-card-title">{{ $whyItems[1]['title'] }}</h3>
              <p class="bb-card-desc">{{ $whyItems[1]['description'] }}</p>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="bb-feature-card">
              <div class="bb-icon-badge">
                <svg width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M7.5 20C3.35938 20 0 16.6406 0 12.5C0 8.9375 5.08594 2.25391 6.50781 0.457031C6.74219 0.164062 7.08984 0 7.46484 0H7.53516C7.91016 0 8.25781 0.164062 8.49219 0.457031C9.91406 2.25391 15 8.9375 15 12.5C15 16.6406 11.6406 20 7.5 20ZM3.75 13.125C3.75 12.7812 3.46875 12.5 3.125 12.5C2.78125 12.5 2.5 12.7812 2.5 13.125C2.5 15.543 4.45703 17.5 6.875 17.5C7.21875 17.5 7.5 17.2188 7.5 16.875C7.5 16.5312 7.21875 16.25 6.875 16.25C5.14844 16.25 3.75 14.8516 3.75 13.125Z"
                    fill="white" />
                </svg>
              </div>
              <h3 class="bb-card-title">{{ $whyItems[2]['title'] }}</h3>
              <p class="bb-card-desc">{{ $whyItems[2]['description'] }}</p>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="bb-feature-card">
              <div class="bb-icon-badge">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M20 1.25C20 5.6875 16.6953 9.35547 12.4141 9.92188C12.1367 7.83594 11.2188 5.95312 9.86328 4.48047C11.3594 1.80859 14.2188 0 17.5 0H18.75C19.4414 0 20 0.558594 20 1.25ZM0 3.75C0 3.05859 0.558594 2.5 1.25 2.5H2.5C7.33203 2.5 11.25 6.41797 11.25 11.25V12.5V18.75C11.25 19.4414 10.6914 20 10 20C9.30859 20 8.75 19.4414 8.75 18.75V12.5C3.91797 12.5 0 8.58203 0 3.75Z"
                    fill="white" />
                </svg>
              </div>
              <h3 class="bb-card-title">{{ $whyItems[3]['title'] }}</h3>
              <p class="bb-card-desc">{{ $whyItems[3]['description'] }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ═══════════════════════════
         SECTION 3: WHAT WE DO
    ═══════════════════════════ -->
  <section class="section-what-we-do" id="what-we-do">
    <div class="container">
      <div class="row align-items-center g-4 g-lg-5">

        <!-- LEFT: Text Content -->
        <div class="col-12 col-lg-6">
          <div class="wwd-content">

            <h2 class="wwd-heading fade-up fade-up-1">{{ $homepageConfig['what_we_do_title'] }}</h2>

            <p class="wwd-desc fade-up fade-up-2">{{ $homepageConfig['what_we_do_description'] }}</p>

            <p class="wwd-solutions-label fade-up fade-up-3">{{ $homepageConfig['what_we_do_label'] }}</p>

            <div class="wwd-crops-grid fade-up fade-up-4">
              <div class="wwd-crop-item">
                <span class="crop-icon">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M15.7805 1.27852C16.0742 0.984767 16.0742 0.509767 15.7805 0.219142C15.4867 -0.0714828 15.0117 -0.0746078 14.7211 0.219142L11.968 2.96602C11.6742 3.25977 11.6742 3.73477 11.968 4.02539C12.2617 4.31602 12.7367 4.31914 13.0273 4.02539L15.7773 1.27539L15.7805 1.27852ZM9.54609 0.850392C9.35234 0.656642 9.03359 0.656642 8.83984 0.850392L8.48359 1.20352C7.31172 2.37539 7.31172 4.27539 8.48359 5.44727L8.80859 5.77227L7.85547 6.72539C7.74922 5.87227 7.37109 5.04414 6.71484 4.38789L6.36172 4.03477C6.16797 3.84102 5.84922 3.84102 5.65547 4.03477L5.30234 4.38789C4.13047 5.55977 4.13047 7.45977 5.30234 8.63164L5.62734 8.95664L4.67422 9.90977C4.56797 9.05664 4.18984 8.22852 3.53359 7.57227L3.18047 7.21602C2.98672 7.02227 2.66797 7.02227 2.47422 7.21602L2.12109 7.56914C0.949219 8.74102 0.949219 10.641 2.12109 11.8129L2.44609 12.1379L0.292969 14.291C-0.0976562 14.6816 -0.0976562 15.316 0.292969 15.7066C0.683594 16.0973 1.31797 16.0973 1.70859 15.7066L3.86172 13.5535L4.24297 13.9348C5.41484 15.1066 7.31484 15.1066 8.48672 13.9348L8.83984 13.5816C9.03359 13.3879 9.03359 13.0691 8.83984 12.8754L8.48672 12.5223C7.80547 11.841 6.93672 11.4566 6.04609 11.3691L7.04297 10.3723L7.42422 10.7535C8.59609 11.9254 10.4961 11.9254 11.668 10.7535L12.0211 10.4004C12.2148 10.2066 12.2148 9.88789 12.0211 9.69414L11.668 9.34102C10.9867 8.65977 10.118 8.27539 9.22734 8.18789L10.2242 7.19102L10.6055 7.57227C11.7773 8.74414 13.6773 8.74414 14.8492 7.57227L15.2023 7.21602C15.3961 7.02227 15.3961 6.70352 15.2023 6.50977L14.8492 6.15352C14.6867 5.99102 14.518 5.84727 14.3367 5.71914L15.7805 4.27852C16.0742 3.98477 16.0742 3.50977 15.7805 3.21914C15.4867 2.92852 15.0117 2.92539 14.7211 3.21914L12.8648 5.07539C12.2211 4.93789 11.5523 4.95977 10.918 5.14102C11.1086 4.47539 11.1242 3.77227 10.9617 3.10039L12.7805 1.27852C13.0742 0.984767 13.0742 0.509767 12.7805 0.219142C12.4867 -0.0714828 12.0117 -0.0746078 11.7211 0.219142L10.2836 1.65039C10.168 1.49414 10.0398 1.34414 9.89609 1.20352L9.54609 0.850392Z"
                      fill="#194E20" />
                  </svg>
                </span>
                <span>{{ $whatWeDoItems[0]['label'] }}</span>
              </div>
              <div class="wwd-crop-item">
                <span class="crop-icon">
                  <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_34_364)">
                      <path
                        d="M7 3.5C6.725 3.5 6.5 3.275 6.5 3V2.5C6.5 1.11875 7.61875 0 9 0H9.5C9.775 0 10 0.225 10 0.5V1C10 2.38125 8.88125 3.5 7.5 3.5H7ZM0 9C0 6.61562 1.11562 4 3.5 4C4.35313 4 5.36562 4.32188 6.08437 4.60313C6.67188 4.83125 7.33125 4.83125 7.91875 4.60313C8.63437 4.325 9.65 4 10.5031 4C12.8875 4 14.0031 6.61562 14.0031 9C14.0031 13 11.5031 16 9.00313 16C8.4875 16 7.8125 15.7938 7.39375 15.6469C7.14062 15.5594 6.86563 15.5594 6.6125 15.6469C6.19375 15.7938 5.51875 16 5.00313 16C2.5 16 0 13 0 9Z"
                        fill="#194E20" />
                    </g>
                    <defs>
                      <clipPath id="clip0_34_364">
                        <path d="M0 0H14V16H0V0Z" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                </span>
                <span>{{ $whatWeDoItems[1]['label'] }}</span>
              </div>
              <div class="wwd-crop-item">
                <span class="crop-icon">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_34_370)">
                      <path
                        d="M10.8344 0.1875C10.55 0.53125 9.99998 1.32187 9.99998 2.25C9.99998 3.5 10.4781 3.97813 11.25 4.75C12.0219 5.52187 12.5 6 13.75 6C14.6781 6 15.4687 5.45 15.8125 5.16563C15.9375 5.0625 16 4.90938 16 4.75C16 4.59062 15.9375 4.4375 15.8125 4.3375C15.4562 4.05312 14.6156 3.5 13.5 3.5C12.5 3.5 12.25 3.75 12.25 3.75C12.25 3.75 12.5 3.5 12.5 2.5C12.5 1.38437 11.9469 0.54375 11.6625 0.1875C11.5625 0.0625 11.4094 0 11.25 0C11.0906 0 10.9375 0.0625 10.8344 0.1875ZM7.64373 4.25C6.39373 4.25 5.23435 4.81562 4.4656 5.75625L6.35623 7.64687C6.54998 7.84062 6.54998 8.15937 6.35623 8.35312C6.16248 8.54688 5.84373 8.54688 5.64998 8.35312L3.92185 6.625V6.62813L0.0687261 14.9344C-0.0625239 15.2188 -0.0031489 15.5562 0.218726 15.7812C0.440601 16.0063 0.778101 16.0625 1.0656 15.9312L5.27498 13.9812L3.64685 12.3531C3.4531 12.1594 3.4531 11.8406 3.64685 11.6469C3.8406 11.4531 4.15935 11.4531 4.3531 11.6469L6.2406 13.5312L9.37185 12.0813C10.8219 11.4094 11.7531 9.95625 11.7531 8.35625C11.75 6.0875 9.91248 4.25 7.64373 4.25Z"
                        fill="#194E20" />
                    </g>
                    <defs>
                      <clipPath id="clip0_34_370">
                        <path d="M0 0H16V16H0V0Z" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                </span>
                <span>{{ $whatWeDoItems[2]['label'] }}</span>
              </div>
              <div class="wwd-crop-item">
                <span class="crop-icon">
                  <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M5.72188 6.35313C6.775 7 7.6875 7.85625 8.40312 8.86875C8.62187 9.17812 8.82187 9.50312 9 9.8375C9.17813 9.5 9.37813 9.17812 9.59688 8.86875C10.3125 7.85625 11.225 7 12.2781 6.35313C13.675 5.49375 15.3156 5 17.0625 5H17.3719C17.7188 5 18 5.28125 18 5.62813C18 10.2531 14.2531 14 9.62813 14H9H8.37187C3.74687 14 0 10.2531 0 5.62813C0 5.28125 0.28125 5 0.628125 5H0.9375C2.68438 5 4.325 5.49375 5.72188 6.35313ZM9.42188 0.175C9.9125 0.703125 11.3313 2.41875 12.0594 5.31875C10.8719 5.99375 9.82812 6.90625 9 7.99375C8.17188 6.90625 7.12813 5.99687 5.94063 5.31875C6.66563 2.41875 8.08438 0.703125 8.57812 0.175C8.6875 0.059375 8.84062 0 9 0C9.15938 0 9.3125 0.059375 9.42188 0.175Z"
                      fill="#194E20" />
                  </svg>
                </span>
                <span>{{ $whatWeDoItems[3]['label'] }}</span>
              </div>
            </div>

          </div>
        </div>

        <!-- RIGHT: Image -->
        <div class="col-12 col-lg-6 fade-up fade-up-5">
          <div class="wwd-image-wrapper">
            <img src="{{ $homepageConfig['what_we_do_image_url'] }}" alt="Young plant seedling growing from rich soil"
              loading="lazy" />
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ═══════════════════════════
         SECTION 4: WHO WE SERVE
    ═══════════════════════════ -->
  <section class="section-who-we-serve" id="who-we-serve">
    <div class="container">

      <h2 class="wws-title fade-up fu-1">{{ $homepageConfig['who_we_serve_title'] }}</h2>
      <span class="wws-underline fade-up fu-2"></span>

      <div class="wws-cards-row">

        <!-- Card 1 -->
        <div class="wws-card fade-up fu-3">
          <div class="wws-icon-circle">
            <svg width="21" height="24" viewBox="0 0 21 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_34_392)">
                <path
                  d="M10.5 12C8.9087 12 7.38258 11.3679 6.25736 10.2426C5.13214 9.11742 4.5 7.5913 4.5 6C4.5 4.4087 5.13214 2.88258 6.25736 1.75736C7.38258 0.632141 8.9087 0 10.5 0C12.0913 0 13.6174 0.632141 14.7426 1.75736C15.8679 2.88258 16.5 4.4087 16.5 6C16.5 7.5913 15.8679 9.11742 14.7426 10.2426C13.6174 11.3679 12.0913 12 10.5 12ZM9.80156 16.8375L8.92969 15.3844C8.62969 14.8828 8.99062 14.25 9.57187 14.25H10.5H11.4234C12.0047 14.25 12.3656 14.8875 12.0656 15.3844L11.1938 16.8375L12.7594 22.6453L14.4469 15.7594C14.5406 15.3797 14.9062 15.1312 15.2859 15.2297C18.5719 16.0547 21 19.0266 21 22.5609C21 23.3578 20.3531 24 19.5609 24H13.3828C13.2844 24 13.1953 23.9812 13.1109 23.9484L13.125 24H7.875L7.88906 23.9484C7.80469 23.9812 7.71094 24 7.61719 24H1.43906C0.646875 24 0 23.3531 0 22.5609C0 19.0219 2.43281 16.05 5.71406 15.2297C6.09375 15.1359 6.45938 15.3844 6.55313 15.7594L8.24063 22.6453L9.80625 16.8375H9.80156Z"
                  fill="white" />
              </g>
              <defs>
                <clipPath id="clip0_34_392">
                  <path d="M0 0H21V24H0V0Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <p class="wws-card-label">{{ $whoWeServeItems[0]['label'] }}</p>
        </div>

        <!-- Card 2 -->
        <div class="wws-card fade-up fu-4">
          <div class="wws-icon-circle">
            <svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_34_401)">
                <path
                  d="M6.75 0C7.74456 0 8.69839 0.395088 9.40165 1.09835C10.1049 1.80161 10.5 2.75544 10.5 3.75C10.5 4.74456 10.1049 5.69839 9.40165 6.40165C8.69839 7.10491 7.74456 7.5 6.75 7.5C5.75544 7.5 4.80161 7.10491 4.09835 6.40165C3.39509 5.69839 3 4.74456 3 3.75C3 2.75544 3.39509 1.80161 4.09835 1.09835C4.80161 0.395088 5.75544 0 6.75 0ZM24 0C24.9946 0 25.9484 0.395088 26.6516 1.09835C27.3549 1.80161 27.75 2.75544 27.75 3.75C27.75 4.74456 27.3549 5.69839 26.6516 6.40165C25.9484 7.10491 24.9946 7.5 24 7.5C23.0054 7.5 22.0516 7.10491 21.3484 6.40165C20.6451 5.69839 20.25 4.74456 20.25 3.75C20.25 2.75544 20.6451 1.80161 21.3484 1.09835C22.0516 0.395088 23.0054 0 24 0ZM0 14.0016C0 11.2406 2.24062 9 5.00156 9H7.00312C7.74844 9 8.45625 9.16406 9.09375 9.45469C9.03281 9.79219 9.00469 10.1438 9.00469 10.5C9.00469 12.2906 9.79219 13.8984 11.0344 15C11.025 15 11.0156 15 11.0016 15H0.998437C0.45 15 0 14.55 0 14.0016ZM18.9984 15C18.9891 15 18.9797 15 18.9656 15C20.2125 13.8984 20.9953 12.2906 20.9953 10.5C20.9953 10.1438 20.9625 9.79688 20.9062 9.45469C21.5438 9.15938 22.2516 9 22.9969 9H24.9984C27.7594 9 30 11.2406 30 14.0016C30 14.5547 29.55 15 29.0016 15H18.9984ZM10.5 10.5C10.5 9.30653 10.9741 8.16193 11.818 7.31802C12.6619 6.47411 13.8065 6 15 6C16.1935 6 17.3381 6.47411 18.182 7.31802C19.0259 8.16193 19.5 9.30653 19.5 10.5C19.5 11.6935 19.0259 12.8381 18.182 13.682C17.3381 14.5259 16.1935 15 15 15C13.8065 15 12.6619 14.5259 11.818 13.682C10.9741 12.8381 10.5 11.6935 10.5 10.5ZM6 22.7484C6 19.2984 8.79844 16.5 12.2484 16.5H17.7516C21.2016 16.5 24 19.2984 24 22.7484C24 23.4375 23.4422 24 22.7484 24H7.25156C6.5625 24 6 23.4422 6 22.7484Z"
                  fill="white" />
              </g>
              <defs>
                <clipPath id="clip0_34_401">
                  <path d="M0 0H30V24H0V0Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <p class="wws-card-label">{{ $whoWeServeItems[1]['label'] }}</p>
        </div>

        <!-- Card 3 -->
        <div class="wws-card fade-up fu-5">
          <div class="wws-icon-circle">
            <svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M24.9201 4.86563L22.2342 0.614063C21.9951 0.234375 21.5685 0 21.1139 0H4.38886C3.93417 0 3.50761 0.234375 3.26855 0.614063L0.577924 4.86563C-0.809576 7.05938 0.418549 10.1109 3.01074 10.4625C3.19824 10.4859 3.39042 10.5 3.57792 10.5C4.80136 10.5 5.88886 9.96562 6.63417 9.14062C7.37949 9.96562 8.46699 10.5 9.69042 10.5C10.9139 10.5 12.0014 9.96562 12.7467 9.14062C13.492 9.96562 14.5795 10.5 15.8029 10.5C17.031 10.5 18.1139 9.96562 18.8592 9.14062C19.6092 9.96562 20.692 10.5 21.9154 10.5C22.1076 10.5 22.2951 10.4859 22.4826 10.4625C25.0842 10.1156 26.317 7.06406 24.9248 4.86563H24.9201ZM22.6748 11.9484H22.6701C22.4217 11.9812 22.1685 12 21.9107 12C21.3295 12 20.7717 11.9109 20.2514 11.7516V18H5.25136V11.7469C4.72636 11.9109 4.16386 12 3.58261 12C3.3248 12 3.06699 11.9812 2.81855 11.9484H2.81386C2.62167 11.9203 2.43417 11.8875 2.25136 11.8406V18V21C2.25136 22.6547 3.59667 24 5.25136 24H20.2514C21.906 24 23.2514 22.6547 23.2514 21V18V11.8406C23.0639 11.8875 22.8764 11.925 22.6748 11.9484Z"
                fill="white" />
            </svg>
          </div>
          <p class="wws-card-label">{{ $whoWeServeItems[2]['label'] }}</p>
        </div>

        <!-- Card 4 -->
        <div class="wws-card fade-up fu-6">
          <div class="wws-icon-circle">
            <svg width="27" height="24" viewBox="0 0 27 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M6.9375 3.59062C6.9375 1.60781 8.54531 0 10.5281 0C11.4797 0 12.3938 0.379687 13.0641 1.05L13.5 1.48594L13.9359 1.05C14.6062 0.379687 15.5203 0 16.4719 0C18.4547 0 20.0625 1.60781 20.0625 3.59062C20.0625 4.54219 19.6828 5.45625 19.0125 6.12656L14.1609 10.9734C13.7953 11.3391 13.2 11.3391 12.8344 10.9734L7.9875 6.12656C7.31719 5.45625 6.9375 4.54219 6.9375 3.59062ZM26.6344 15.7641C27.2484 16.5984 27.0703 17.7703 26.2359 18.3844L20.3016 22.7578C19.2047 23.5641 17.8828 24 16.5187 24H9H1.5C0.670312 24 0 23.3297 0 22.5V19.5C0 18.6703 0.670312 18 1.5 18H3.225L5.32969 16.3125C6.39375 15.4594 7.71562 15 9.07969 15H12.75H13.5H16.5C17.3297 15 18 15.6703 18 16.5C18 17.3297 17.3297 18 16.5 18H13.5H12.75C12.3375 18 12 18.3375 12 18.75C12 19.1625 12.3375 19.5 12.75 19.5H18.4031L24.0141 15.3656C24.8484 14.7516 26.0203 14.9297 26.6344 15.7641ZM9.075 18H9.03281C9.04688 18 9.06094 18 9.075 18Z"
                fill="white" />
            </svg>
          </div>
          <p class="wws-card-label">{{ $whoWeServeItems[3]['label'] }}</p>
        </div>

        <!-- Card 5 -->
        <div class="wws-card fade-up fu-7">
          <div class="wws-icon-circle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_34_427)">
                <path
                  d="M7.5 1.5C7.5 0.670312 8.17031 0 9 0H10.5C11.3297 0 12 0.670312 12 1.5C12.8297 1.5 13.5 2.17031 13.5 3V13.5C13.5 14.3297 12.8297 15 12 15C12 15.8297 11.3297 16.5 10.5 16.5H9C8.17031 16.5 7.5 15.8297 7.5 15C6.67031 15 6 14.3297 6 13.5V3C6 2.17031 6.67031 1.5 7.5 1.5ZM1.5 21H15C18.3141 21 21 18.3141 21 15C21 11.6859 18.3141 9 15 9V6C19.9688 6 24 10.0312 24 15C24 17.3062 23.1328 19.4062 21.7078 21H22.5C23.3297 21 24 21.6703 24 22.5C24 23.3297 23.3297 24 22.5 24H15H1.5C0.670312 24 0 23.3297 0 22.5C0 21.6703 0.670312 21 1.5 21ZM5.25 18H14.25C14.6625 18 15 18.3375 15 18.75C15 19.1625 14.6625 19.5 14.25 19.5H5.25C4.8375 19.5 4.5 19.1625 4.5 18.75C4.5 18.3375 4.8375 18 5.25 18Z"
                  fill="white" />
              </g>
              <defs>
                <clipPath id="clip0_34_427">
                  <path d="M0 0H24V24H0V0Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <p class="wws-card-label">{{ $whoWeServeItems[4]['label'] }}</p>
        </div>

      </div>

    </div>
  </section>


  <!-- ═══════════════════════════
         SECTION 5: KEY HIGHLIGHTS
    ═══════════════════════════ -->
  <section class="section-key-highlights" id="key-highlights">
    <div class="container">

      <h2 class="kh-title fade-up fu-1">{{ $homepageConfig['key_highlights_title'] }}</h2>
      <p class="kh-subtitle fade-up fu-2">{{ $homepageConfig['key_highlights_subtitle'] }}</p>

      <div class="kh-cards-row">

        <!-- Card 1 -->
        <div class="kh-card fade-up fu-3">
          <div class="kh-icon-circle">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_34_442)">
                <path
                  d="M9.16992 26.2324L8.43164 27.9609C7.33594 27.4043 6.32812 26.7188 5.41406 25.916L6.74414 24.5859C7.47656 25.2246 8.29102 25.7812 9.16992 26.2324ZM2.37891 15.9375H0.498047C0.580078 17.1797 0.814453 18.3809 1.18359 19.5176L2.92969 18.8203C2.64258 17.9004 2.44922 16.9336 2.37891 15.9375ZM2.37891 14.0625C2.46094 12.9609 2.68359 11.8945 3.0293 10.8926L1.30078 10.1543C0.861328 11.3848 0.585938 12.6973 0.498047 14.0625H2.37891ZM3.76758 9.16992C4.22461 8.29688 4.77539 7.48242 5.41406 6.73828L4.08398 5.4082C3.28125 6.32227 2.58984 7.33008 2.03906 8.42578L3.76758 9.16992ZM23.2617 24.5859C22.4473 25.2891 21.5391 25.8926 20.5605 26.3672L21.2578 28.1133C22.4707 27.5332 23.5898 26.7891 24.5918 25.9102L23.2617 24.5859ZM6.73828 5.41406C7.55273 4.71094 8.46094 4.10742 9.43945 3.63281L8.74219 1.88672C7.5293 2.4668 6.41016 3.21094 5.41406 4.08984L6.73828 5.41406ZM26.2324 20.8301C25.7754 21.7031 25.2246 22.5176 24.5859 23.2617L25.916 24.5918C26.7188 23.6777 27.4102 22.6641 27.9609 21.5742L26.2324 20.8301ZM27.6211 15.9375C27.5391 17.0391 27.3164 18.1055 26.9707 19.1074L28.6992 19.8457C29.1387 18.6094 29.4141 17.2969 29.4961 15.9316H27.6211V15.9375ZM18.8203 27.0703C17.9004 27.3633 16.9336 27.5508 15.9375 27.6211V29.502C17.1797 29.4199 18.3809 29.1855 19.5176 28.8164L18.8203 27.0703ZM14.0625 27.6211C12.9609 27.5391 11.8945 27.3164 10.8926 26.9707L10.1543 28.6992C11.3906 29.1387 12.7031 29.4141 14.0684 29.4961V27.6211H14.0625ZM27.0703 11.1797C27.3633 12.0996 27.5508 13.0664 27.6211 14.0625H29.502C29.4199 12.8203 29.1855 11.6191 28.8164 10.4824L27.0703 11.1797ZM5.41406 23.2617C4.71094 22.4473 4.10742 21.5391 3.63281 20.5605L1.88672 21.2578C2.4668 22.4707 3.21094 23.5898 4.08984 24.5918L5.41406 23.2617ZM15.9375 2.37891C17.0391 2.46094 18.0996 2.68359 19.1074 3.0293L19.8457 1.30078C18.6152 0.861328 17.3027 0.585938 15.9375 0.498047V2.37891ZM11.1797 2.92969C12.0996 2.63672 13.0664 2.44922 14.0625 2.37891V0.498047C12.8203 0.580078 11.6191 0.814453 10.4824 1.18359L11.1797 2.92969ZM25.916 5.4082L24.5859 6.73828C25.2891 7.55273 25.8926 8.46094 26.373 9.43945L28.1191 8.74219C27.5391 7.5293 26.7949 6.41016 25.916 5.4082ZM23.2617 5.41406L24.5918 4.08398C23.6777 3.28125 22.6699 2.58984 21.5742 2.03906L20.8359 3.76758C21.7031 4.22461 22.5234 4.77539 23.2617 5.41406Z"
                  fill="white" />
                <path
                  d="M15 22.9688C15.9061 22.9688 16.6406 22.2342 16.6406 21.3281C16.6406 20.422 15.9061 19.6875 15 19.6875C14.0939 19.6875 13.3594 20.422 13.3594 21.3281C13.3594 22.2342 14.0939 22.9688 15 22.9688Z"
                  fill="white" />
                <path
                  d="M15.4512 18.2812H14.5137C14.127 18.2812 13.8106 17.9648 13.8106 17.5781C13.8106 13.418 18.3458 13.834 18.3458 11.2617C18.3458 10.0898 17.3028 8.90625 14.9825 8.90625C13.2774 8.90625 12.3868 9.46875 11.5137 10.5879C11.2852 10.8809 10.8633 10.9395 10.5645 10.7285L9.79694 10.1895C9.46882 9.96094 9.39265 9.49805 9.6446 9.18164C10.8868 7.58789 12.3633 6.5625 14.9883 6.5625C18.0528 6.5625 20.6954 8.30859 20.6954 11.2617C20.6954 15.2227 16.1602 14.9824 16.1602 17.5781C16.1544 17.9648 15.838 18.2812 15.4512 18.2812Z"
                  fill="white" />
              </g>
              <defs>
                <clipPath id="clip0_34_442">
                  <path d="M0 0H30V30H0V0Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <p class="kh-card-title">{{ $highlightItems[0]['title'] }}</p>
          <p class="kh-card-desc">{{ $highlightItems[0]['description'] }}</p>
        </div>

        <!-- Card 2 -->
        <div class="kh-card fade-up fu-4">
          <div class="kh-icon-circle">
            <svg width="23" height="30" viewBox="0 0 23 30" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_34_454)">
                <path
                  d="M12.5742 2.42578C11.8418 1.69336 10.6523 1.69336 9.91992 2.42578L0.544922 11.8008C-0.1875 12.5332 -0.1875 13.7227 0.544922 14.4551C1.27734 15.1875 2.4668 15.1875 3.19922 14.4551L9.375 8.27344V26.25C9.375 27.2871 10.2129 28.125 11.25 28.125C12.2871 28.125 13.125 27.2871 13.125 26.25V8.27344L19.3008 14.4492C20.0332 15.1816 21.2227 15.1816 21.9551 14.4492C22.6875 13.7168 22.6875 12.5273 21.9551 11.7949L12.5801 2.41992L12.5742 2.42578Z"
                  fill="white" />
              </g>
              <defs>
                <clipPath id="clip0_34_454">
                  <path d="M0 0H22.5V30H0V0Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <p class="kh-card-title">{{ $highlightItems[1]['title'] }}</p>
          <p class="kh-card-desc">{{ $highlightItems[1]['description'] }}</p>
        </div>

        <!-- Card 3 -->
        <div class="kh-card fade-up fu-5">
          <div class="kh-icon-circle">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_34_462)">
                <path
                  d="M15 0C15.2696 0 15.5391 0.0585938 15.7852 0.169922L26.8184 4.85156C28.1074 5.39648 29.0684 6.66797 29.0625 8.20312C29.0332 14.0156 26.6426 24.6504 16.5469 29.4844C15.5684 29.9531 14.4317 29.9531 13.4532 29.4844C3.35745 24.6504 0.966824 14.0156 0.937527 8.20312C0.931667 6.66797 1.8926 5.39648 3.18167 4.85156L14.2207 0.169922C14.461 0.0585938 14.7305 0 15 0ZM15 3.91406V26.0625C23.086 22.1484 25.2598 13.4824 25.3125 8.28516L15 3.91406Z"
                  fill="white" />
              </g>
              <defs>
                <clipPath id="clip0_34_462">
                  <path d="M0 0H30V30H0V0Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <p class="kh-card-title">{{ $highlightItems[2]['title'] }}</p>
          <p class="kh-card-desc">{{ $highlightItems[2]['description'] }}</p>
        </div>

        <!-- Card 4 -->
        <div class="kh-card fade-up fu-6">
          <div class="kh-icon-circle">
            <svg width="30" height="27" viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M15.9375 3.75441C11.332 3.75441 7.43555 6.77199 6.11133 10.9321C8.08008 9.93605 10.3008 9.37941 12.6562 9.37941H17.8125C18.3281 9.37941 18.75 9.80129 18.75 10.3169C18.75 10.8325 18.3281 11.2544 17.8125 11.2544H16.875H12.6562C11.6836 11.2544 10.7402 11.3657 9.83203 11.5708C8.31445 11.9165 6.90234 12.5318 5.64844 13.3696C2.24414 15.6372 0 19.5103 0 23.9107V24.8482C0 25.6275 0.626953 26.2544 1.40625 26.2544C2.18555 26.2544 2.8125 25.6275 2.8125 24.8482V23.9107C2.8125 21.0571 4.02539 18.4907 5.96484 16.6919C7.125 21.1157 11.1504 24.3794 15.9375 24.3794H15.9961C23.7363 24.3384 30 16.7095 30 7.30519C30 4.8091 29.5605 2.43605 28.7637 0.29738C28.6113 -0.106917 28.0195 -0.089339 27.8145 0.29152C26.7129 2.35402 24.5332 3.75441 22.0312 3.75441H15.9375Z"
                fill="white" />
            </svg>
          </div>
          <p class="kh-card-title">{{ $highlightItems[3]['title'] }}</p>
          <p class="kh-card-desc">{{ $highlightItems[3]['description'] }}</p>
        </div>

      </div>
    </div>
  </section>

  @if($homepageConfig['video_reviews_enabled'])
    @php
      $pvrCards = [
        ['name' => 'Rahul Mehta', 'avatar' => 'RM', 'quote' => 'BrainBoost changed exam season for us. His focus is insane.', 'stars' => 5, 'bg' => 'pvr-bg-1'],
        ['name' => 'Dr. Anita Nair', 'avatar' => 'AN', 'quote' => 'As a pediatrician, I recommend NutriBuddy with full confidence.', 'stars' => 5, 'bg' => 'pvr-bg-2'],
        ['name' => 'Fatima Khan', 'avatar' => 'FK', 'quote' => 'DreamCalm turned bedtime from nightmare into our favorite time.', 'stars' => 5, 'bg' => 'pvr-bg-3'],
        ['name' => 'Vikram Patel', 'avatar' => 'VP', 'quote' => 'Both kids on different NutriBuddy plans. Life-changing.', 'stars' => 5, 'bg' => 'pvr-bg-4'],
        ['name' => 'Sneha Joshi', 'avatar' => 'SJ', 'quote' => 'My toddler asks for his gummy before breakfast. That is a win.', 'stars' => 4, 'bg' => 'pvr-bg-5'],
        ['name' => 'Priya Sharma', 'avatar' => 'PS', 'quote' => 'Noticed a difference in just 2 weeks. Highly recommended.', 'stars' => 5, 'bg' => 'pvr-bg-6'],
        ['name' => 'Arjun Kapoor', 'avatar' => 'AK', 'quote' => 'Our pediatrician was surprised at how quickly he improved.', 'stars' => 5, 'bg' => 'pvr-bg-7'],
      ];

      $resolvePvrVideoUrl = function ($url) {
        $url = trim((string) $url);
        if ($url === '') {
          return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
          return $url;
        }

        $normalizedPath = ltrim($url, '/');
        if (str_starts_with($normalizedPath, 'storage/')) {
          return asset($normalizedPath);
        }
        if (str_starts_with($normalizedPath, 'public/')) {
          return asset('storage/' . ltrim(substr($normalizedPath, 7), '/'));
        }
        if (is_file(public_path($normalizedPath))) {
          return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
      };

      $pvrItems = $videoReviews->isNotEmpty()
        ? $videoReviews->map(function ($item) use ($resolvePvrVideoUrl) {
            $rawUrl = trim((string) ($item['instagram_url'] ?? $item['video_url'] ?? ''));
            $videoUrl = null;
            if ($rawUrl !== '' && preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $rawUrl)) {
              $videoUrl = $resolvePvrVideoUrl($rawUrl);
            }

            return [
              'title' => $item['title'] ?? 'Video Review',
              'video_url' => $videoUrl,
            ];
          })->filter(fn ($item) => !empty($item['video_url']))->values()
        : collect($pvrCards)->map(function ($item) {
            return [
              'title' => $item['name'],
              'video_url' => null,
              'bg' => $item['bg'],
              'name' => $item['name'],
              'avatar' => $item['avatar'],
              'quote' => $item['quote'],
              'stars' => $item['stars'],
            ];
          })->values();

      $pvrItems = $pvrItems->take(5)->values();
    @endphp
    <section class="pvr-section-wrap">
      <div class="container">
        <section class="pvr-section">
          <div class="pvr-header">
            <h2 class="pvr-title">Instagram Reels</h2>
          </div>

          <div class="pvr-track-outer">
            <div class="pvr-track" id="pvrTrack">
              @foreach($pvrItems as $card)
                <article class="pvr-card {{ !empty($card['video_url']) ? 'pvr-card--video' : '' }}" aria-label="Review of {{ $card['title'] }}">
                  @if(!empty($card['video_url']))
                    <video class="pvr-video" playsinline webkit-playsinline preload="metadata" loop autoplay muted defaultMuted>
                      <source src="{{ $card['video_url'] }}" type="video/mp4">
                    </video>
                  @else
                    <div class="pvr-thumb {{ $card['bg'] }}"></div>
                  @endif
                  <div class="pvr-overlay"></div>

                  @if(empty($card['video_url']))
                    <div class="pvr-stars">
                      @for($star = 0; $star < $card['stars']; $star++)
                        <span></span>
                      @endfor
                    </div>
                    <div class="pvr-user-row">
                      <div class="pvr-avatar">{{ $card['avatar'] }}</div>
                      <span class="pvr-name">{{ $card['name'] }}</span>
                    </div>
                    <p class="pvr-quote">"{{ $card['quote'] }}"</p>
                  @else
                    <div class="pvr-meta">
                      <span class="pvr-name">{{ $card['title'] }}</span>
                    </div>
                  @endif
                </article>
              @endforeach
            </div>
          </div>
        </section>
      </div>
    </section>
  @endif

@endsection

@push('styles')
  <style>
    @import url('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');

    .bb-media-showcase {
      padding: 5rem 0;
      background:
        radial-gradient(circle at top left, rgba(126, 176, 112, 0.2), transparent 32%),
        linear-gradient(180deg, #f6fbf2 0%, #eef7e8 100%);
    }

    .bb-media-showcase__shell {
      border-radius: 32px;
      background: rgba(255, 255, 255, 0.8);
      border: 1px solid rgba(45, 122, 69, 0.1);
      box-shadow: 0 24px 60px rgba(21, 61, 31, 0.08);
      padding: 2rem;
    }

    .bb-media-showcase__eyebrow {
      display: inline-block;
      color: #2d7a45;
      font-weight: 700;
      letter-spacing: .16em;
      text-transform: uppercase;
      font-size: .75rem;
      margin-bottom: .9rem;
    }

    .bb-media-showcase__title {
      font-size: clamp(2rem, 4vw, 3.2rem);
      color: #183420;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 1rem;
    }

    .bb-media-showcase__desc {
      color: #516b57;
      max-width: 620px;
      margin-bottom: 1.5rem;
      font-size: 1rem;
      line-height: 1.8;
    }

    .bb-media-card,
    .bb-instagram-card {
      border-radius: 24px;
      overflow: hidden;
      background: #fff;
      border: 1px solid #ddebd7;
      box-shadow: 0 20px 45px rgba(33, 66, 43, 0.08);
    }

    .bb-media-card iframe,
    .bb-media-card video {
      border: 0;
      width: 100%;
      height: 100%;
      display: block;
      background: #0f1d13;
    }

    .bb-instagram-card {
      min-height: 100%;
      padding: 1rem;
    }

    .bb-instagram-card iframe,
    .bb-instagram-card blockquote {
      max-width: 100% !important;
      min-width: 100% !important;
      width: 100% !important;
    }

    .bb-instagram-embed {
      margin: 0 !important;
      min-width: 100% !important;
      max-width: 100% !important;
      width: 100% !important;
    }

    .bb-hero-video-frame,
    .bb-hero-video {
      width: 100%;
      border: 0;
      border-radius: 28px;
      background: #0e1b12;
      overflow: hidden;
    }

    .bb-hero-video-frame iframe {
      width: 100%;
      height: 100%;
      border: 0;
    }

    .bb-hero-swiper {
      padding-bottom: 72px;
    }

    .bb-why-swiper {
      padding: 1rem 0 4.5rem;
    }

    .bb-why-swiper .swiper-slide {
      height: auto;
    }

    .bb-why-swiper .bb-feature-card {
      height: 100%;
    }

    .bb-why-swiper__nav {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      z-index: 4;
    }

    .bb-why-swiper__button {
      width: 44px;
      height: 44px;
      border: 0;
      border-radius: 50%;
      background: #21422b;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      box-shadow: 0 16px 28px rgba(33, 66, 43, 0.18);
    }

    .bb-why-swiper__pagination {
      position: static;
      width: auto !important;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .bb-why-swiper__pagination .swiper-pagination-bullet {
      width: 10px;
      height: 10px;
      margin: 0 !important;
      background: rgba(34, 95, 52, 0.28);
      opacity: 1;
    }

    .bb-why-swiper__pagination .swiper-pagination-bullet-active {
      background: #2d7a45;
    }

    .bb-hero-swiper__nav {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      z-index: 4;
    }

    .bb-hero-swiper__button {
      width: 44px;
      height: 44px;
      border: 0;
      border-radius: 50%;
      background: #21422b;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      box-shadow: 0 16px 28px rgba(33, 66, 43, 0.22);
    }

    .bb-hero-swiper__pagination {
      position: static;
      width: auto !important;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .bb-hero-swiper__pagination .swiper-pagination-bullet {
      width: 10px;
      height: 10px;
      margin: 0 !important;
      background: rgba(34, 95, 52, 0.28);
      opacity: 1;
    }

    .bb-hero-swiper__pagination .swiper-pagination-bullet-active {
      background: #2d7a45;
    }

    .bb-video-tabs {
      gap: .75rem;
      flex-wrap: wrap;
    }

    .bb-video-tabs .nav-link {
      border-radius: 999px;
      padding: .8rem 1.25rem;
      border: 1px solid #dbe9d5;
      background: #fff;
      color: #21422b;
      font-weight: 600;
    }

    .bb-video-tabs .nav-link.active {
      background: #2d7a45;
      border-color: #2d7a45;
      color: #fff;
    }

    .bb-video-review-copy {
      padding: 1rem 0;
    }

    .bb-video-review-copy__kicker {
      display: inline-block;
      margin-bottom: .8rem;
      color: #2d7a45;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
    }

    .bb-video-review-copy h3,
    .bb-mobile-video-card__title {
      color: #183420;
      font-weight: 800;
      margin-bottom: 1rem;
    }

    .bb-video-review-copy p {
      color: #516b57;
      line-height: 1.8;
      margin-bottom: 1.25rem;
    }

    .bb-mobile-video-card {
      padding: 0 2.25rem 1rem;
    }

    .bb-video-reviews .carousel-control-prev,
    .bb-video-reviews .carousel-control-next {
      width: 2.4rem;
    }

    .bb-video-reviews .carousel-control-prev-icon,
    .bb-video-reviews .carousel-control-next-icon {
      background-color: rgba(24, 52, 32, 0.78);
      border-radius: 50%;
      background-size: 55%;
      width: 2.4rem;
      height: 2.4rem;
    }

    .pvr-section-wrap {
      padding: 4rem 0 5rem;
         background: #eef5ee;
    }

    .pvr-section {
      max-width: 1300px;
      margin: 0 auto;
    }

    .pvr-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      padding: 0 4px;
    }

    .pvr-title {
      font-size: 22px;
      font-weight: 700;
      color: #1a1a2e;
      letter-spacing: -0.3px;
      margin: 0;
    }

    .pvr-track-outer {
      overflow: visible;
      position: relative;
    }

    .pvr-track {
      display: grid;
      grid-template-columns: repeat(5, minmax(0, 1fr));
      gap: 16px;
    }

    .pvr-card {
      width: 100%;
      min-width: 0;
      border-radius: 20px;
      overflow: hidden;
      background: #1a1a1a;
      position: relative;
      aspect-ratio: 9 / 16;
      transition: transform 0.2s, box-shadow 0.2s;
      border: 0;
      padding: 0;
    }

    .pvr-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
    }

    .pvr-thumb {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
      background: #2a2a2a;
      object-fit: cover;
    }

    .pvr-video {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
      background: #000;
    }

    .pvr-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, transparent 40%, rgba(0, 0, 0, 0.75) 100%);
      border-radius: 20px;
      z-index: 2;
      pointer-events: none;
    }

    .pvr-play-btn {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 48px;
      height: 48px;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(4px);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
      pointer-events: none;
      z-index: 3;
    }

    .pvr-play-btn svg {
      width: 20px;
      height: 20px;
      fill: #fff;
    }

    .pvr-card.pvr-playing .pvr-play-btn svg.pvr-icon-play {
      display: none;
    }

    .pvr-card:not(.pvr-playing) .pvr-play-btn svg.pvr-icon-pause {
      display: none;
    }

    .pvr-progress {
      position: absolute;
      top: 12px;
      left: 12px;
      right: 12px;
      height: 3px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 2px;
      overflow: hidden;
      z-index: 3;
    }

    .pvr-progress-fill {
      height: 100%;
      background: #fff;
      border-radius: 2px;
      width: 0%;
      transition: width 0.1s linear;
    }

    .pvr-card.pvr-playing .pvr-progress-fill {
      width: 40%;
      animation: pvr-prog 8s linear infinite;
    }

    @keyframes pvr-prog {
      from {
        width: 0%;
      }

      to {
        width: 100%;
      }
    }

    .pvr-stars {
      position: absolute;
      bottom: 72px;
      left: 14px;
      display: flex;
      gap: 2px;
      color: #f5b800;
      font-size: 14px;
      line-height: 1;
    }

    .pvr-user-row {
      position: absolute;
      bottom: 41px;
      left: 14px;
      right: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .pvr-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 2px solid #f5b800;
      background: #f5b800;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      color: #1b1b1b;
      flex-shrink: 0;
    }

    .pvr-name {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .pvr-meta {
      position: absolute;
      left: 14px;
      right: 14px;
      bottom: 12px;
      z-index: 3;
    }

    .pvr-quote {
      position: absolute;
      bottom: 8px;
      left: 14px;
      right: 14px;
      font-size: 10.5px;
      color: rgba(255, 255, 255, 0.82);
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin: 0;
    }

    .pvr-bg-1 {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .pvr-bg-2 {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .pvr-bg-3 {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .pvr-bg-4 {
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .pvr-bg-5 {
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .pvr-bg-6 {
      background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
    }

    .pvr-bg-7 {
      background: linear-gradient(135deg, #fda085 0%, #f6d365 100%);
    }

    @media (max-width: 991.98px) {
      .bb-hero-swiper {
        padding-bottom: 58px;
      }

      .bb-hero-swiper__button {
        width: 38px;
        height: 38px;
      }

      .bb-why-swiper {
        padding-bottom: 4rem;
      }

      .bb-why-swiper__button {
        width: 38px;
        height: 38px;
      }

      .pvr-track {
        grid-template-columns: repeat(4, minmax(0, 1fr));
      }
    }

    @media (max-width: 860px) {
      .pvr-track-outer {
        overflow-x: auto;
        overflow-y: hidden;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
      }

      .pvr-track-outer::-webkit-scrollbar {
        display: none;
      }

      .pvr-track {
        display: flex;
        gap: 12px;
        width: max-content;
        height: 346px;
        padding-right: 8px;
      }

      .pvr-card {
        flex: 0 0 clamp(220px, 62vw, 300px);
        scroll-snap-align: start;
      }
    }

    @media (max-width: 600px) {
      .pvr-card {
        flex-basis: clamp(210px, 74vw, 280px);
      }

      .pvr-title {
        font-size: 18px;
      }
    }

    @media (max-width: 400px) {
      .pvr-card {
        flex-basis: clamp(200px, 84vw, 260px);
      }
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script async src="https://www.instagram.com/embed.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const heroSwiperElement = document.querySelector('.bb-hero-swiper');

      if (heroSwiperElement && typeof Swiper !== 'undefined') {
        const heroSlideCount = heroSwiperElement.querySelectorAll('.swiper-slide').length;
        new Swiper(heroSwiperElement, {
          loop: heroSlideCount > 1,
          speed: 700,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.bb-hero-swiper__pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.bb-hero-swiper__button--next',
            prevEl: '.bb-hero-swiper__button--prev',
          },
        });
      }

      const whySwiperElement = document.querySelector('.bb-why-swiper');

      if (whySwiperElement && typeof Swiper !== 'undefined') {
        const whySlideCount = whySwiperElement.querySelectorAll('.swiper-slide').length;
        const enableWhyLoop = whySlideCount > 4;
        new Swiper(whySwiperElement, {
          loop: enableWhyLoop,
          speed: 650,
          spaceBetween: 24,
          autoplay: {
            delay: 4200,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.bb-why-swiper__pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.bb-why-swiper__button--next',
            prevEl: '.bb-why-swiper__button--prev',
          },
          breakpoints: {
            0: {
              slidesPerView: 1.1,
            },
            576: {
              slidesPerView: 1.4,
            },
            768: {
              slidesPerView: 2,
            },
            1200: {
              slidesPerView: 4,
            },
          },
        });
      }

      const processInstagramEmbeds = function () {
        if (window.instgrm && window.instgrm.Embeds) {
          window.instgrm.Embeds.process();
        }
      };

      processInstagramEmbeds();

      document.querySelectorAll('[data-bs-toggle="pill"]').forEach(function (tabButton) {
        tabButton.addEventListener('shown.bs.tab', processInstagramEmbeds);
      });

      const mobileReviews = document.getElementById('bbVideoReviewsMobile');
      if (mobileReviews) {
        mobileReviews.addEventListener('slid.bs.carousel', processInstagramEmbeds);
      }

      const pvrTrack = document.getElementById('pvrTrack');
      if (pvrTrack) {
        const pvrVideos = pvrTrack.querySelectorAll('.pvr-video');

        function ensureMutedAutoplay(video) {
          if (!video) return;
          video.muted = true;
          video.defaultMuted = true;
          video.loop = true;
          video.setAttribute('muted', '');
          video.setAttribute('autoplay', '');
          video.setAttribute('playsinline', '');
          video.setAttribute('webkit-playsinline', '');
          const playPromise = video.play();
          if (playPromise && typeof playPromise.catch === 'function') {
            playPromise.catch(function () {});
          }
        }

        pvrVideos.forEach(function (video) {
          ensureMutedAutoplay(video);
          video.addEventListener('loadeddata', function () {
            ensureMutedAutoplay(video);
          }, { once: true });
          video.addEventListener('canplay', function () {
            ensureMutedAutoplay(video);
          }, { once: true });
        });
      }

      const sliderEl = document.getElementById('slider');
      if (sliderEl) {
        const slides = sliderEl.querySelectorAll('.slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        if (slides.length > 1 && prevBtn && nextBtn) {
          let current = 0;
          let isAnimating = false;
          let autoTimer = null;
          const AUTO_DELAY = 5000;
          const ANIM_MS = 850;

          function goTo(next, direction = 'next') {
            if (isAnimating || next === current) return;
            isAnimating = true;

            const prev = current;
            current = next;
            const incoming = slides[next];
            const outgoing = slides[prev];

            slides.forEach((slide, index) => {
              if (index !== prev && index !== next) {
                slide.classList.remove('active', 'exit-left', 'exit-right', 'from-left');
              }
            });

            incoming.classList.remove('active', 'exit-left', 'exit-right', 'from-left');
            outgoing.classList.remove('exit-left', 'exit-right', 'from-left');

            if (direction === 'prev') {
              incoming.classList.add('from-left');
            }

            incoming.classList.add('active');
            incoming.offsetHeight;

            requestAnimationFrame(() => {
              incoming.classList.remove('from-left');
              outgoing.classList.add(direction === 'next' ? 'exit-left' : 'exit-right');
              outgoing.classList.remove('active');
            });

            setTimeout(() => {
              outgoing.classList.remove('exit-left', 'exit-right', 'from-left');
              isAnimating = false;
            }, ANIM_MS);
          }

          function next() {
            goTo((current + 1) % slides.length, 'next');
          }

          function prev() {
            goTo((current - 1 + slides.length) % slides.length, 'prev');
          }

          function startAuto() {
            clearInterval(autoTimer);
            autoTimer = setInterval(next, AUTO_DELAY);
          }

          function resetAuto() {
            clearInterval(autoTimer);
            startAuto();
          }

          nextBtn.addEventListener('click', () => { next(); resetAuto(); });
          prevBtn.addEventListener('click', () => { prev(); resetAuto(); });

          sliderEl.addEventListener('click', (e) => {
            if (e.target.closest('a, button, .controls')) return;
            const rect = sliderEl.getBoundingClientRect();
            const x = e.clientX - rect.left;
            x < rect.width / 2 ? prev() : next();
            resetAuto();
          });

          let touchStartX = 0;
          sliderEl.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
          }, { passive: true });

          sliderEl.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) {
              diff > 0 ? next() : prev();
              resetAuto();
            }
          }, { passive: true });

          startAuto();
        }
      }
    });
  </script>
@endpush
