@extends('layout.frontlayout')
@section('title', 'Bharat Biomer – Nature-Powered Biology')

@section('content')
  <!-- ========================
       SECTION 1: Hero - Innovation
  ======================== -->
  <x-front-breadcrumb
    badge="Our Product Portfolio"
    title="Innovation in Every Formulation"
    description="Advanced bio-stimulants and agricultural solutions designed for modern farming challenges. From microbial formulations to smart fertilizers."
    :icon="asset('assets/images/flask-icon.svg')"
  />

  <!-- ========================
       SECTION 2: Available Now
  ======================== -->
  <section class="avan__section">
    <div class="container">

      <!-- Top Header -->
      <div class="row">
        <div class="col-12">
          <div class="avan__header">
            <div class="avan__header-top">
              
              <h3 class="avan__header-title">Available Now</h3>
            </div>
            <p class="avan__header-desc">Proven formulations ready for field application</p>
          </div>
        </div>
      </div>

      <!-- Product Card -->
      <div class="row">
        <div class="col-12">
          <div class="avan__card">
            <div class="row g-0">

              <!-- Left: Product images -->
              <div class="col-12 col-md-5">
                <img src="assets/images/product-bottle.svg" alt="Pink Symbions" class="avan__product-img"/>
              </div>

              <!-- Right: Product Content -->
              <div class="col-12 col-md-7">
                <div class="avan__content">

                  <h3 class="avan__product-title">PPFM-Based Bio-Stimulant</h3>
                  <p class="avan__product-desc">A high-performance microbial formulation designed to improve flowering, nutrient efficiency, and stress tolerance across crops.</p>

                  <!-- Key Features -->
                  <h5 class="avan__features-heading">Key Features</h5>
                  <div class="row g-3 mb-4">

                    <div class="col-12 col-sm-6">
                      <div class="avan__feature-item">
                        <div class="avan__feature-icon-wrap">
                          <img src="assets/images/dosage-icon.svg" alt="Low Dosage" class="avan__feature-icon"/>
                        </div>
                        <div>
                          <p class="avan__feature-title">Very Low Dosage</p>
                          <p class="avan__feature-desc">Cost-effective application</p>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-sm-6">
                      <div class="avan__feature-item">
                        <div class="avan__feature-icon-wrap">
                          <img src="assets/images/foliar-icon.svg" alt="Foliar Application" class="avan__feature-icon"/>
                        </div>
                        <div>
                          <p class="avan__feature-title">Foliar Application</p>
                          <p class="avan__feature-desc">Easy to apply</p>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-sm-6">
                      <div class="avan__feature-item">
                        <div class="avan__feature-icon-wrap">
                          <img src="assets/images/compatible-icon.svg" alt="Compatible" class="avan__feature-icon"/>
                        </div>
                        <div>
                          <p class="avan__feature-title">Highly Compatible</p>
                          <p class="avan__feature-desc">Works with existing practices</p>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-sm-6">
                      <div class="avan__feature-item">
                        <div class="avan__feature-icon-wrap">
                          <img src="assets/images/multicrop-icon.svg" alt="Multi-Crop" class="avan__feature-icon"/>
                        </div>
                        <div>
                          <p class="avan__feature-title">Multi-Crop</p>
                          <p class="avan__feature-desc">Versatile application</p>
                        </div>
                      </div>
                    </div>

                  </div>

                  <!-- Crop Suitability -->
                  <h5 class="avan__features-heading">Crop Suitability</h5>
                  <div class="avan__tags-wrap">
                    <span class="avan__tag">Wheat</span>
                    <span class="avan__tag">Paddy</span>
                    <span class="avan__tag">Maize</span>
                    <span class="avan__tag">Pulses & Oilseeds</span>
                    <span class="avan__tag">Cotton</span>
                    <span class="avan__tag">Vegetables & Fruits</span>
                    <span class="avan__tag">Flowers & Plantation</span>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ========================
     SECTION 3: Product Pipeline
======================== -->
<section class="ppip__section">
  <div class="container">

    <!-- Header -->
    <div class="row">
      <div class="col-12">
        <div class="ppip__header-top">
          <img src="assets/images/clock-icon.svg" alt="clock" class="ppip__header-icon"/>
          <h3 class="ppip__header-title">Product Pipeline</h3>
        </div>
        <p class="ppip__header-desc">Next-generation solutions under development</p>
      </div>
    </div>

    <!-- 4 Cards -->
    <div class="row g-4 mt-2">

      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="ppip__card">
          <span class="ppip__badge">Coming Soon</span>
          <div class="ppip__icon-wrap">
            <img src="assets/images/fertilizer-icon.svg" alt="Smart Fertilizers" class="ppip__icon"/>
          </div>
          <h4 class="ppip__card-title">Smart Fertilizers</h4>
          <p class="ppip__card-desc">Intelligent nutrient delivery systems with controlled release mechanisms</p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="ppip__card">
          <span class="ppip__badge">Coming Soon</span>
          <div class="ppip__icon-wrap">
            <img src="assets/images/consortia-icon.svg" alt="Microbial Consortia" class="ppip__icon"/>
          </div>
          <h4 class="ppip__card-title">Microbial Consortia</h4>
          <p class="ppip__card-desc">Advanced multi-strain formulations for enhanced soil health</p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="ppip__card">
          <span class="ppip__badge">Coming Soon</span>
          <div class="ppip__icon-wrap">
            <img src="assets/images/biopolymer-icon.svg" alt="Biopolymer Inputs" class="ppip__icon"/>
          </div>
          <h4 class="ppip__card-title">Biopolymer Inputs</h4>
          <p class="ppip__card-desc">Sustainable polymer-based agricultural enhancement solutions</p>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="ppip__card">
          <span class="ppip__badge">Coming Soon</span>
          <div class="ppip__icon-wrap">
            <img src="assets/images/climate-icon.svg" alt="Climate-Resilient" class="ppip__icon"/>
          </div>
          <h4 class="ppip__card-title">Climate-Resilient</h4>
          <p class="ppip__card-desc">Formulations designed for extreme weather and climate stress</p>
        </div>
      </div>

    </div>
  </div>
</section>

 @endsection
