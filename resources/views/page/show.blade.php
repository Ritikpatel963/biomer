@extends('layout.frontlayout')
@section('title', $page->meta_title ?? $page->title . ' – Bharat Biomer')

@push('meta')
    {{-- Meta Description --}}
    <meta name="description" content="{{ $page->meta_description ?? 'Bharat Biomer - Advanced Biometric Solutions' }}">
    
    {{-- Meta Keywords --}}
    @if($page->meta_keyword)
        <meta name="keywords" content="{{ $page->meta_keyword }}">
    @endif
    
    {{-- Open Graph Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $page->meta_title ?? $page->title }}">
    <meta property="og:description" content="{{ $page->meta_description ?? 'Bharat Biomer' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Bharat Biomer">
    
    {{-- Open Graph Image (default) --}}
    <meta property="og:image" content="{{ asset('assets/images/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    {{-- Twitter Card Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page->meta_title ?? $page->title }}">
    <meta name="twitter:description" content="{{ $page->meta_description ?? 'Bharat Biomer' }}">
    <meta name="twitter:image" content="{{ asset('assets/images/og-image.png') }}">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            {{-- Page Title --}}
            <h1 class="mb-4">{{ $page->title }}</h1>
            
            {{-- Page Content --}}
            <div class="page-content">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
