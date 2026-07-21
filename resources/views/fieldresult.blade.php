@extends('layout.frontlayout')
@section('title', $pageSeo?->meta_title ?: 'Impact & Field Results - Bharat Biomer')
@section('seo_description', $pageSeo?->meta_description ?: 'See Bharat Biomer field results and impact from nature-powered biological solutions across crop performance, soil health, and sustainable agriculture.')
@section('seo_keywords', $pageSeo?->meta_keyword ?: 'Bharat Biomer impact, field results, crop performance, sustainable agriculture, soil health')

@section('content')

  <!-- ========================
       SECTION 1: Hero - Impact & Field Results
  ======================== -->
  <x-front-breadcrumb
    badge="Field Results"
    title="Impact & Field Results"
    description="Real-world evidence of PPFM technology transforming agriculture across India"
  />

  <!-- ========================
       SECTION 2: Observed Field Benefits
  ======================== -->
  <section class="ofb__section">
    <div class="container">

      <!-- Header -->
      <div class="row">
        <div class="col-12">
          <h2 class="ofb__heading">Observed Field Benefits</h2>
          <p class="ofb__subtext">Consistent improvements documented across multiple trials and farming conditions</p>
        </div>
      </div>

      <!-- 4 Cards -->
      <div class="row g-4 mt-3">

        <!-- Card 1 -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ofb__card">
            <div class="ofb__icon-wrap">
              <img src="assets/images/flowering-icon.svg" alt="Flowering" class="ofb__icon"/>
            </div>
            <h5 class="ofb__card-title">Increased Flowering</h5>
            <p class="ofb__card-desc">Enhanced flowering and fruit retention rates across test crops</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ofb__card">
            <div class="ofb__icon-wrap">
              <img src="assets/images/uniformity-icon.svg" alt="Uniformity" class="ofb__icon"/>
            </div>
            <h5 class="ofb__card-title">Improved Uniformity</h5>
            <p class="ofb__card-desc">Better crop uniformity and consistent plant development</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ofb__card">
            <div class="ofb__icon-wrap">
              <img src="assets/images/growth-icon.svg" alt="Growth" class="ofb__icon"/>
            </div>
            <h5 class="ofb__card-title">Stronger Growth</h5>
            <p class="ofb__card-desc">Enhanced root and shoot development for healthier plants</p>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ofb__card">
            <div class="ofb__icon-wrap">
              <img src="assets/images/stress-icon.svg" alt="Stress Tolerance" class="ofb__icon"/>
            </div>
            <h5 class="ofb__card-title">Stress Tolerance</h5>
            <p class="ofb__card-desc">Better tolerance to heat and moisture stress conditions</p>
          </div>
        </div>

      </div>
    </div>
  </section>
<!-- ========================
     SECTION 3: Farmer-Level Impact
======================== -->
<section class="fli__section">
  <div class="container">

    <!-- Heading -->
    <div class="row justify-content-center">
      <div class="col-12 text-center">
        <h2 class="fli__heading">Farmer-Level Impact</h2>
        <p class="fli__subtext">Tangible benefits delivered to farming communities</p>
      </div>
    </div>

    <!-- Light Green Flow Box -->
    <div class="fli__flow-box mt-4">
      <div class="row align-items-center justify-content-center">

        <!-- Step 1 -->
        <div class="col-12 col-md-auto text-center">
          <div class="fli__icon-wrap mx-auto mb-3">
            <img src="assets/images/input-reduction-icon.svg" alt="Input Reduction" class="fli__icon"/>
          </div>
          <h5 class="fli__step-title">Input Reduction</h5>
          <p class="fli__step-desc">Lower cultivation costs</p>
        </div>

        <!-- Arrow -->
        <div class="col-12 col-md-auto text-center">
          <span class="fli__arrow">→</span>
        </div>

        <!-- Step 2 -->
        <div class="col-12 col-md-auto text-center">
          <div class="fli__icon-wrap mx-auto mb-3">
            <img src="assets/images/yield-icon.svg" alt="Yield Increase" class="fli__icon"/>
          </div>
          <h5 class="fli__step-title">Yield Increase</h5>
          <p class="fli__step-desc">Better productivity</p>
        </div>

        <!-- Arrow -->
        <div class="col-12 col-md-auto text-center">
          <span class="fli__arrow">→</span>
        </div>

        <!-- Step 3 -->
        <div class="col-12 col-md-auto text-center">
          <div class="fli__icon-wrap mx-auto mb-3">
            <img src="assets/images/income-icon.svg" alt="Income Growth" class="fli__icon"/>
          </div>
          <h5 class="fli__step-title">Income Growth</h5>
          <p class="fli__step-desc">Improved profitability</p>
        </div>

      </div>
    </div>

    <!-- 4 Dark Green Stat Cards -->
    <div class="row g-4 mt-3">

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="fli__stat-card">
          <h3 class="fli__stat-number">↑ 15-25%</h3>
          <p class="fli__stat-label">Productivity Increase</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="fli__stat-card">
          <h3 class="fli__stat-number">↓ 20%</h3>
          <p class="fli__stat-label">Cultivation Risk</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="fli__stat-card">
          <h3 class="fli__stat-number">1:3</h3>
          <p class="fli__stat-label">Cost-Benefit Ratio</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="fli__stat-card">
          <h3 class="fli__stat-number">30%+</h3>
          <p class="fli__stat-label">Better Stress Response</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ========================
     SECTION 4: Sustainability & Carbon Impact
======================== -->
<section class="sci__section">
  <div class="container">

    <!-- Heading -->
    <div class="row justify-content-center">
      <div class="col-12 text-center">
        <div class="sci__top-icon-wrap mx-auto mb-3">
          <img src="assets/images/leaf-icon.svg" alt="Leaf" class="sci__top-icon"/>
        </div>
        <h2 class="sci__heading">Sustainability & Carbon Impact</h2>
        <p class="sci__subtext">Aligning agriculture with climate-positive and regenerative goals</p>
      </div>
    </div>

    <!-- Sustainability Philosophy Card -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="sci__philosophy-card">

          <!-- Title -->
          <div class="sci__philosophy-title-wrap">
            <span class="sci__quote">"</span>
            <h3 class="sci__philosophy-title">Our Sustainability Philosophy</h3>
          </div>
          <p class="sci__philosophy-desc">If nature creates the solution, the solution should go back to nature—without harming it.</p>

          <!-- Dark Green Nature Box -->
          <div class="sci__nature-box">
            <div class="sci__nature-icons">
              <img src="assets/images/globe-solid-icon.svg" alt="Globe" class="sci__nature-icon"/>
              <span class="sci__plus">+</span>
              <img src="assets/images/plant-small-icon.svg" alt="Plant" class="sci__nature-icon"/>
              <span class="sci__plus">+</span>
              <img src="assets/images/mountain-solid-icon.svg" alt="Mountain" class="sci__nature-icon"/>
            </div>
            <p class="sci__nature-label">Nature-to-Nature Circular System</p>
          </div>

        </div>
      </div>
    </div>

    <!-- Environmental Benefits & Carbon Perspective -->
    <div class="row g-4 mt-4">

      <!-- Environmental Benefits -->
      <div class="col-12 col-md-6">
        <div class="sci__white-card">
          <div class="sci__white-card-header">
            <img src="assets/images/eco-icon.svg" alt="Eco" class="sci__card-header-icon"/>
            <h4 class="sci__white-card-title">Environmental Benefits</h4>
          </div>
          <ul class="sci__benefit-list">
            <li class="sci__benefit-item">
              <img src="assets/images/droplet-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Reduced Chemical Runoff</p>
                <p class="sci__benefit-desc">Minimises water contamination and ecosystem disruption</p>
              </div>
            </li>
            <li class="sci__benefit-item">
              <img src="assets/images/soil-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Improved Soil Biological Activity</p>
                <p class="sci__benefit-desc">Enhances microbial diversity and nutrient cycling</p>
              </div>
            </li>
            <li class="sci__benefit-item">
              <img src="assets/images/regen-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Supports Regenerative Agriculture</p>
                <p class="sci__benefit-desc">Builds resilient and self-sustaining farming systems</p>
              </div>
            </li>
            <li class="sci__benefit-item">
              <img src="assets/images/longterm-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Enhances Long-term Soil Health</p>
                <p class="sci__benefit-desc">Promotes sustained fertility and productivity</p>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Carbon Perspective -->
      <div class="col-12 col-md-6">
        <div class="sci__white-card">
          <div class="sci__white-card-header">
            <img src="assets/images/cloud-icon.svg" alt="Carbon" class="sci__card-header-icon"/>
            <h4 class="sci__white-card-title">Carbon Perspective</h4>
          </div>
          <p class="sci__carbon-intro">Biological inputs like PPFM support:</p>
          <ul class="sci__benefit-list">
            <li class="sci__benefit-item">
              <img src="assets/images/organic-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Improved Soil Organic Matter</p>
                <p class="sci__benefit-desc">Carbon sequestration through biological processes</p>
              </div>
            </li>
            <li class="sci__benefit-item">
              <img src="assets/images/footprint-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Reduced Chemical Input Footprint</p>
                <p class="sci__benefit-desc">Lower manufacturing and transportation emissions</p>
              </div>
            </li>
            <li class="sci__benefit-item">
              <img src="assets/images/ecosystem-icon.svg" alt="" class="sci__benefit-icon"/>
              <div>
                <p class="sci__benefit-title">Sustainable Farming Ecosystems</p>
                <p class="sci__benefit-desc">Long-term environmental and economic balance</p>
              </div>
            </li>
          </ul>
        </div>
      </div>

    </div>

    <!-- Carbon Impact Flow -->
    <div class="row justify-content-center add-margin">
      <div class="col-12 text-center">
        <h3 class="sci__flow-heading">Carbon Impact Flow</h3>
      </div>
    </div>

    <div class="row justify-content-center align-items-center mt-4 g-0">

      <div class="col-12 col-md-auto text-center">
        <div class="sci__flow-icon-wrap mx-auto mb-5">
          <img src="assets/images/flask-icon-green.svg" alt="Biological Input" class="sci__flow-icon"/>
        </div>
        <h5 class="sci__flow-title">Biological Input</h5>
        <p class="sci__flow-desc">PPFM Application</p>
      </div>

      <div class="col-12 col-md-auto text-center px-3">
        <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M25.6992 12.5786C26.4316 11.8462 26.4316 10.6567 25.6992 9.92432L16.3242 0.549316C15.5918 -0.183105 14.4023 -0.183105 13.6699 0.549316C12.9375 1.28174 12.9375 2.47119 13.6699 3.20361L19.8516 9.37939H1.875C0.837891 9.37939 0 10.2173 0 11.2544C0 12.2915 0.837891 13.1294 1.875 13.1294H19.8457L13.6758 19.3052C12.9434 20.0376 12.9434 21.2271 13.6758 21.9595C14.4082 22.6919 15.5977 22.6919 16.3301 21.9595L25.7051 12.5845L25.6992 12.5786Z" fill="#494949"/>
</svg>

      </div>

      <div class="col-12 col-md-auto text-center">
        <div class="sci__flow-icon-wrap mx-auto mb-5">
          <img src="assets/images/mountain-outline-icon.svg" alt="Soil Health" class="sci__flow-icon"/>
        </div>
        <h5 class="sci__flow-title">Soil Health</h5>
        <p class="sci__flow-desc">Enhanced Biology</p>
      </div>

      <div class="col-12 col-md-auto text-center px-3">
       <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M25.6992 12.5786C26.4316 11.8462 26.4316 10.6567 25.6992 9.92432L16.3242 0.549316C15.5918 -0.183105 14.4023 -0.183105 13.6699 0.549316C12.9375 1.28174 12.9375 2.47119 13.6699 3.20361L19.8516 9.37939H1.875C0.837891 9.37939 0 10.2173 0 11.2544C0 12.2915 0.837891 13.1294 1.875 13.1294H19.8457L13.6758 19.3052C12.9434 20.0376 12.9434 21.2271 13.6758 21.9595C14.4082 22.6919 15.5977 22.6919 16.3301 21.9595L25.7051 12.5845L25.6992 12.5786Z" fill="#494949"/>
</svg>

      </div>

      <div class="col-12 col-md-auto text-center">
        <div class="sci__flow-icon-wrap mx-auto mb-5">
          <img src="assets/images/globe-outline-icon.svg" alt="Ecosystem Balance" class="sci__flow-icon"/>
        </div>
        <h5 class="sci__flow-title">Ecosystem Balance</h5>
        <p class="sci__flow-desc">Climate Positive</p>
      </div>

    </div>

    <!-- Bottom CTA -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="sci__cta-card text-center">
          <h4 class="sci__cta-heading">Bharat Biomer aligns with climate-positive and regenerative agriculture goals</h4>
          <p class="sci__cta-desc">Supporting CSR initiatives and grant-funded sustainability programs</p>
          <a href="#" class="sci__cta-btn">Learn More About Our Impact</a>
        </div>
      </div>
    </div>

  </div>
</section>

@endsection
