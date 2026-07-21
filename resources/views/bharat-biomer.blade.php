@extends('layout.frontlayout')

@section('title', $pageSeo?->meta_title ?: 'Bharat Biomer | Nature-powered Biological Solutions')
@section('seo_description', $pageSeo?->meta_description ?: 'Biological farming solutions for better yield, crop health, and soil vitality.')
@section('seo_keywords', $pageSeo?->meta_keyword ?: '')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}?v={{ filemtime(public_path('assets/css/frontend.css')) }}">
@endpush


@php
    $homePageConfig = \App\Models\HomePageSetting::currentMerged();
@endphp

@section('content')
        <section class="hero">
            <div class="wrap hero-grid">
                <div class="hero-copy">
                    <h1><span>Nature-powered</span> biological solutions for better crop yield</h1>
                    <p>Bharat Biomer helps farmers improve crop health, soil vitality, nutrient uptake and farm
                        productivity using science-backed biological inputs.</p>
                    <div class="hero-ctas"><a class="bb-home-btn primary" href="{{ route('products.index') }}">Explore Products <i
                                class="fa fa-long-arrow-right" aria-hidden="true"></i></a><a class="bb-home-btn secondary"
                            href="#finder"><i class="fa fa-pagelines" aria-hidden="true"></i> Find Crop Solution</a>
                    </div>
                </div>
                <div class="hero-art" aria-label="Bharat Biomer farmer and product illustration">
                    <div class="sun"></div>
                    <div class="petal p1"></div>
                    <div class="petal p2"></div>
                    <div class="petal p3"></div>
                    <div class="producthero"><img src="{{ asset('assets/bharat-biomer/banner-1.png') }}"
                            alt="banner" /></div>
                    <div class="hero-badges">
                        <div class="float f1"><img src="{{ asset('assets/bharat-biomer/business-chart.png') }}"
                                alt="icon" /> Higher Yield</div>
                        <div class="float f2"><img src="{{ asset('assets/bharat-biomer/plant-1.png') }}"
                                alt="icon" /> Healthier Soil</div>
                        <div class="float f3"><img src="{{ asset('assets/bharat-biomer/plant.png') }}" alt="icon" />
                            Residue-Free</div>
                        <div class="float f4"><img src="{{ asset('assets/bharat-biomer/science.png') }}"
                                alt="icon" /> Science Backed</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section problem">
            <div class="wrap">
                <h2 class="title">{{ $homePageConfig['problem_heading'] }}</h2>
                <p class="sub">{{ $homePageConfig['problem_paragraph'] }}</p>
                <div class="grid-5">
                    @foreach($homePageConfig['problem_items'] as $item)
                        <div class="card problem-card">
                            <div class="icon">
                                <img src="{{ \App\Models\HomePageSetting::imageUrl($item['image_path'] ?? '') }}"
                                    alt="{{ $item['heading'] ?? 'Challenge icon' }}" />
                            </div>
                            <h3>{{ $item['heading'] }}</h3>
                            <p>{{ $item['paragraph'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section solutions" id="products" data-no-motion>
            <div class="wrap">
                <h2 class="title">{{ $homePageConfig['solution_heading'] }}</h2>
                <p class="sub">{{ $homePageConfig['solution_paragraph'] }}</p>
                <div class="category-grid">
                    @foreach($homePageConfig['solution_items'] as $item)
                        <article class="card cat">
                            <div class="cat-img">
                                <img src="{{ \App\Models\HomePageSetting::imageUrl($item['image_path'] ?? '') }}"
                                    alt="{{ $item['heading'] ?? 'Solution image' }}"
                                    width="640"
                                    height="360"
                                    loading="lazy"
                                    decoding="async">
                            </div>
                            <div class="cat-body">
                                <div class="cat-icon">
                                    <img src="{{ \App\Models\HomePageSetting::imageUrl($item['icon_path'] ?? '') }}"
                                        alt="{{ $item['heading'] ?? 'Solution icon' }}">
                                </div>
                                <h3>{{ $item['heading'] }}</h3>
                                <p>{{ $item['paragraph'] }}</p>
                                <a class="circle-link" href="{{ $item['url'] ?: '#' }}">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section why" id="technology">
            <div class="wrap">
                <h2 class="title">{{ $homePageConfig['why_heading'] }}</h2>
                <p class="sub">{{ $homePageConfig['why_paragraph'] }}</p>
                <div class="why-grid">
                    @foreach($homePageConfig['why_items'] as $item)
                        <div class="card why-card">
                            <div class="icon">
                                <img src="{{ \App\Models\HomePageSetting::imageUrl($item['image_path'] ?? '') }}"
                                    alt="{{ $item['heading'] ?? 'Why Bharat Biomer icon' }}">
                            </div>
                            <h3>{{ $item['heading'] }}</h3>
                            <p>{{ $item['paragraph'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section" id="finder">
            <div class="wrap finder-grid">
                <div class="card finder"><span class="eyebrow">Crop Solution Finder</span>
                    <h2>Find recommended solutions for your crop</h2>
                    <p>Select your crop and get recommended solutions designed for better performance.</p>
                    <label><strong>Choose crop</strong><select class="select" id="cropSelect">
                            <option>Wheat</option>
                            <option>Tomato</option>
                            <option>Cotton</option>
                            <option>Soybean</option>
                            <option>Paddy</option>
                        </select></label><button class="bb-home-btn primary">View Recommended Solution <i
                            class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                    <p><small>Personalized solutions based on crop needs and growth stage.</small></p>
                </div>
                <div class="card recommend">
                    <h3 id="recTitle">Recommended for Wheat</h3>
                    <div id="recList"></div>
                    <p style="text-align:center;color:#526357">Need expert help? Talk to our agronomist <strong
                            style="color:var(--green-700)">WhatsApp</strong></p>
                </div>
            </div>
        </section>

        <section class="section cycle" id="crops">
            <div class="wrap">
                <h2 class="title">Benefits across the crop cycle</h2>
                <p class="sub">From soil activation to yield quality, Bharat Biomer supports the complete crop
                    journey.</p>
                <div class="cycle-row">
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/plant.png') }}"
                                alt="icon" /></div>
                        <h3>Soil</h3>
                        <p>Improves soil structure and microbial activity.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/root.png') }}"
                                alt="icon" /></div>
                        <h3>Root</h3>
                        <p>Strengthens roots for better anchorage.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/npk.png') }}"
                                alt="icon" /></div>
                        <h3>Nutrient</h3>
                        <p>Enhances nutrient uptake and efficiency.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/flower-pot.png') }}"
                                alt="icon" /></div>
                        <h3>Flowering</h3>
                        <p>Promotes uniform flowering and setting.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/healthy-food.png') }}"
                                alt="icon" /></div>
                        <h3>Fruiting</h3>
                        <p>Supports healthy fruit development.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/plant-1.png') }}"
                                alt="icon" /></div>
                        <h3>Yield</h3>
                        <p>Increases yield and improves quality.</p>
                    </div>
                </div>
                <div class="cycle-dots" data-cycle-dots aria-label="Crop cycle slider pagination"></div>
            </div>
        </section>

        <section class="stats"
            style="background-image: url('{{ \App\Models\HomePageSetting::imageUrl($homePageConfig['stats_background_image'] ?? '') }}'); background-size: cover; background-position: 50% 20%;">
            <div class="wrap stats-grid">
                @foreach($homePageConfig['stats_items'] as $item)
                    <div class="stat">
                        <i>
                            <img src="{{ \App\Models\HomePageSetting::imageUrl($item['icon_path'] ?? '') }}"
                                alt="{{ $item['heading'] ?? 'Stats icon' }}" style="margin: 0 auto;" />
                        </i>
                        <strong>{{ $item['number'] }}</strong>
                        <span>{{ $item['heading'] }}</span>
                        <small>{{ $item['paragraph'] }}</small>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="section stories" id="stories">
            @php
                $storyCards = collect($homePageConfig['story_items'])->map(function ($item) {
                    $storyVideoUrl = \App\Models\HomePageSetting::videoEmbedUrl($item['video_url'] ?? '');
                    $hasStoryContent = trim(($item['heading'] ?? '') . ($item['paragraph'] ?? '') . ($item['thumbnail_path'] ?? '') . ($storyVideoUrl ?? '')) !== '';

                    return [
                        'item' => $item,
                        'video_url' => $storyVideoUrl,
                        'has_content' => $hasStoryContent,
                    ];
                })->filter(fn ($storyCard) => $storyCard['has_content'])->values();
            @endphp
            <div class="wrap">
                <h2 class="title">{{ $homePageConfig['story_heading'] }}</h2>
                <p class="sub">{{ $homePageConfig['story_paragraph'] }}</p>
                <div class="stories-grid">
                    @foreach($storyCards as $index => $storyCard)
                        @php
                            $item = $storyCard['item'];
                            $storyVideoUrl = $storyCard['video_url'];
                        @endphp
                        <article class="story {{ $index >= 3 ? 'story--extra is-hidden' : '' }}" @if($index >= 3) data-story-extra @endif>
                            <div class="story-img {{ $storyVideoUrl !== '' ? 'story-img--video' : '' }}"
                                @if($storyVideoUrl !== '') data-story-video="{{ $storyVideoUrl }}" @endif
                                data-story-title="{{ $item['heading'] ?: 'Field story video' }}"
                                style="background-image: url('{{ \App\Models\HomePageSetting::imageUrl($item['thumbnail_path'] ?? '') }}'); background-position: center; background-size: cover;">
                                @if($storyVideoUrl !== '')
                                    <button type="button" class="play" aria-label="Play {{ $item['heading'] ?: 'field story video' }}"></button>
                                    @if(! empty($item['duration']))
                                        <span class="duration">{{ $item['duration'] }}</span>
                                    @endif
                                @else
                                    <div class="person"></div>
                                @endif
                            </div>
                            <h3>{{ $item['heading'] }}</h3>
                            <p>{{ $item['paragraph'] }}</p>
                        </article>
                    @endforeach
                </div>
                @if($storyCards->count() > 3)
                    <div class="stories-actions" style="text-align:center;margin-top:28px">
                        <button type="button" class="bb-home-btn secondary stories-toggle" data-stories-more data-more-text="{{ $homePageConfig['story_button_text'] ?: 'View More Stories' }}">
                            <span>{{ $homePageConfig['story_button_text'] ?: 'View More Stories' }}</span> <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="bb-home-btn secondary stories-toggle is-hidden" data-stories-less>
                            <span>View Less</span> <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
                        </button>
                    </div>
                @elseif(! empty($homePageConfig['story_button_text']))
                    <div style="text-align:center;margin-top:28px">
                        <a class="bb-home-btn secondary" href="{{ $homePageConfig['story_button_url'] ?: '#' }}">
                            {{ $homePageConfig['story_button_text'] }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
            </div>
        </section>

        <section class="partner">
            <div class="wrap">
                <div class="partner-box"
                    style="background-image: url('{{ asset('assets/bharat-biomer/footerfarmer.jpg') }}'); background-size: cover; background-position: 50% 20%;">
                    <h2>Grow with us as a <span>channel partner</span></h2>
                    <p>Join our growing network of distributors and agri-partners. Let's grow together for a sustainable
                        tomorrow.</p>
                    <div class="partner-ctas"><a class="bb-home-btn yellow" href="#">Become a Distributor <i
                                class="fa fa-long-arrow-right" aria-hidden="true"></i></a><a class="bb-home-btn secondary"
                            href="#">Talk to Sales <i class="fa fa-mobile" aria-hidden="true"></i></a></div>
                    <div class="handshake"></div>
                </div>
            </div>
        </section>
    
@endsection
