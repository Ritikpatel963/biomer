@extends('layout.frontlayout')
@section('title', $page->meta_title ?? $page->title . ' – Bharat Biomer')
@section('seo_description', $page->meta_description ?? 'Bharat Biomer - Advanced Biometric Solutions')
@section('seo_keywords', $page->meta_keyword ?? '')

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            {{-- Page Title --}}
            <h1 class="mb-4">{{ $page->title }}</h1>

            {{-- SEO Preview --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 text-dark mb-3">SEO Preview</h2>
                    <p class="mb-1"><strong>Title:</strong> {{ $page->meta_title ?? $page->title }}</p>
                    <p class="mb-1"><strong>Description:</strong> {{ $page->meta_description ?? 'No page description set yet.' }}</p>
                    <p class="mb-1"><strong>Keywords:</strong> {{ $page->meta_keyword ?? 'Not specified' }}</p>
                    <p class="mb-0"><strong>Canonical URL:</strong> <a href="{{ url()->current() }}">{{ url()->current() }}</a></p>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="page-content">
                {!! \App\Services\HtmlSanitizer::clean($page->content) !!}
            </div>
        </div>
    </div>
</div>
