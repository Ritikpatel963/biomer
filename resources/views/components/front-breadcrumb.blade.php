@props([
    'badge' => null,
    'title' => '',
    'description' => null,
    'icon' => null,
    'align' => 'left',
    'homeUrl' => null,
    'homeLabel' => 'Home',
    'showTrail' => true,
    'background' => null,
])

@php
    $homeUrl = $homeUrl ?: url('/');
    $background = $background ?: asset('assets/images/home-img/breadcumb-img.webp');
@endphp

@once
    
@endonce

<section class="fbreadcrumb">
    <img
        src="{{ $background }}"
        alt=""
        class="fbreadcrumb__background"
        width="2138"
        height="736"
        loading="eager"
        decoding="async"
        fetchpriority="high"
        aria-hidden="true"
    >
    <div class="container">
        <div class="fbreadcrumb__inner">
            @if($showTrail)
                <nav class="fbreadcrumb__trail" aria-label="Breadcrumb">
                    <a href="{{ $homeUrl }}">{{ $homeLabel }}</a>
                    <span>/</span>
                    <span>{{ $title }}</span>
                </nav>
            @endif

            @if($badge)
                <div class="fbreadcrumb__badge">
                    @if($icon)
                        <img src="{{ $icon }}" alt="" class="fbreadcrumb__badge-icon">
                    @endif
                    <span>{{ $badge }}</span>
                </div>
            @endif

            <h1 class="fbreadcrumb__title">{{ $title }}</h1>

            @if($description)
                <p class="fbreadcrumb__desc">{{ $description }}</p>
            @endif
        </div>
    </div>
</section>
