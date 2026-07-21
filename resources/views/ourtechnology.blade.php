
@extends('layout.frontlayout')
@section('title', $pageSeo?->meta_title ?: 'Our Technology – Bharat Biomer')
@section('seo_description', $pageSeo?->meta_description ?: 'Discover Bharat Biomer’s PPFM technology and how our biological innovations improve crop growth, resilience, and soil health.')
@section('seo_keywords', $pageSeo?->meta_keyword ?: 'PPFM technology, agriculture biotechnology, crop resilience, soil health, Bharat Biomer technology')

@section('content')
  <!-- ========================
       SECTION 1: Our Technology
  ======================== -->
  <x-front-breadcrumb
    badge="Technology"
    title="Our Technology"
    description="PPFM - Pink Pigmented Facultative Methylotrophs Explained"
    align="center"
  />
  {{--
          <p class="ourtec__hero-subtitle">PPFM – Pink Pigmented Facultative Methylotrophs Explained</p>
        </div>
      </div>
    </div>
  </section>
  --}}

  <!-- ========================
       SECTION 2: What is PPFM
  ======================== -->
  <section class="ppfm__section">
    <div class="container">

      <!-- What is PPFM Text Block -->
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="ppfm__text-block">
            <h2 class="ppfm__heading">What is PPFM?</h2>
            <p class="ppfm__desc">PPFM (Pink Pigmented Facultative Methylotrophs) are naturally occurring beneficial bacteria found on plant leaves and surfaces.</p>
          </div>
        </div>
      </div>

      <!-- Green Diagram Card -->
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="ppfm__green-card">
            <div class="row align-items-center justify-content-center g-0">

              <!-- Plant -->
              <div class="col-12 col-md ppfm__diagram-col text-center">
                <img src="assets/images/plant-icon.svg" alt="Plant Icon" class="ppfm__icon mb-3"/>
                <h4 class="ppfm__col-title">Plant</h4>
                <p class="ppfm__col-desc">Releases methanol<br>as by-product</p>
              </div>

              <!-- Arrow 1 -->
              <div class="col-12 col-md-auto ppfm__arrow-col text-center">
                <div class="ppfm__arrow-wrap">
                  <img src="assets/images/arrow-icon.svg" alt="arrow" class="ppfm__arrow-icon"/>
                  <span class="ppfm__arrow-label">Methanol</span>
                </div>
              </div>

              <!-- PPFM Bacteria -->
              <div class="col-12 col-md ppfm__diagram-col text-center">
                <img src="assets/images/bacteria-icon.svg" alt="Bacteria Icon" class="ppfm__icon mb-3"/>
                <h4 class="ppfm__col-title">PPFM Bacteria</h4>
                <p class="ppfm__col-desc">Utilizes methanol<br>as carbon source</p>
              </div>

              <!-- Arrow 2 -->
              <div class="col-12 col-md-auto ppfm__arrow-col text-center">
                <div class="ppfm__arrow-wrap">
                  <img src="assets/images/arrow-icon.svg" alt="arrow" class="ppfm__arrow-icon"/>
                  <span class="ppfm__arrow-label">Growth Support</span>
                </div>
              </div>

              <!-- Enhanced Plant -->
              <div class="col-12 col-md ppfm__diagram-col text-center">
                <img src="assets/images/enhanced-plant-icon.svg" alt="Enhanced Plant Icon" class="ppfm__icon mb-3"/>
                <h4 class="ppfm__col-title">Enhanced Plant</h4>
                <p class="ppfm__col-desc">Improved growth<br>& resilience</p>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ========================
       SECTION 3: How PPFM Works
  ======================== -->
  <section class="hpw__section">
    <div class="container">

      <!-- Heading -->
      <div class="row justify-content-center">
        <div class="col-12">
          <h2 class="hpw__heading">How PPFM Works: The Symbiotic Process</h2>
        </div>
      </div>

      <!-- 4 Cards Row -->
      <div class="row justify-content-center g-4">

        <!-- Card 1: Plant Growth -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="hpw__card text-center">
            <div class="hpw__icon-wrap">
              <img src="assets/images/plant-growth-icon.svg" alt="Plant Growth" class="hpw__icon"/>
            </div>
            <p class="hpw__card-label">Plant Growth</p>
          </div>
          <div class="hpw__step-block text-center">
            <h4 class="hpw__step-title">Step 1</h4>
            <p class="hpw__step-desc">Plants naturally release methanol during growth processes</p>
          </div>
        </div>

        <!-- Card 2: Methanol Release -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="hpw__card text-center">
            <div class="hpw__icon-wrap">
              <img src="assets/images/methanol-release-icon.svg" alt="Methanol Release" class="hpw__icon"/>
            </div>
            <p class="hpw__card-label">Methanol Release</p>
          </div>
          <div class="hpw__step-block text-center">
            <h4 class="hpw__step-title">Step 2</h4>
            <p class="hpw__step-desc">PPFM bacteria detect and utilize methanol as carbon source</p>
          </div>
        </div>

        <!-- Card 3: Conversion -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="hpw__card text-center">
            <div class="hpw__icon-wrap">
              <img src="assets/images/conversion-icon.svg" alt="Conversion" class="hpw__icon"/>
            </div>
            <p class="hpw__card-label">Conversion</p>
          </div>
          <div class="hpw__step-block text-center">
            <h4 class="hpw__step-title">Step 3</h4>
            <p class="hpw__step-desc">Bacteria convert methanol into beneficial compounds</p>
          </div>
        </div>

        <!-- Card 4: Enhanced Growth -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="hpw__card text-center">
            <div class="hpw__icon-wrap">
              <img src="assets/images/enhanced-growth-icon.svg" alt="Enhanced Growth" class="hpw__icon"/>
            </div>
            <p class="hpw__card-label">Enhanced Growth</p>
          </div>
          <div class="hpw__step-block text-center">
            <h4 class="hpw__step-title">Step 4</h4>
            <p class="hpw__step-desc">Plants receive growth-promoting substances in return</p>
          </div>
        </div>

      </div>
    </div>
  </section>





  <!-- ========================
     SECTION 4: How PPFM Helps Plants
======================== -->
<section class="hph__section" id="technology-crop">
  <div class="container">

    <div class="row justify-content-center">
      <div class="col-12">
        <h2 class="hph__heading">How PPFM Helps Plants</h2>
      </div>
    </div>

    <div class="row g-4">

      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="hph__card text-center">
          <div class="hph__icon-wrap">
            <img src="assets/images/carbon-source-icon.svg" alt="Carbon Source" class="hph__icon"/>
          </div>
          <h4 class="hph__card-title">Carbon Source Utilization</h4>
        </div>
        <p class="hph__card-desc text-center">Uses methanol released by plants as a primary carbon source for metabolism</p>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="hph__card text-center">
          <div class="hph__icon-wrap">
            <img src="assets/images/growth-substances-icon.svg" alt="Growth Substances" class="hph__icon"/>
          </div>
          <h4 class="hph__card-title">Growth Substances</h4>
        </div>
        <p class="hph__card-desc text-center">Produces plant growth-promoting substances that enhance development</p>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="hph__card text-center">
          <div class="hph__icon-wrap">
            <img src="assets/images/stress-tolerance-icon.svg" alt="Stress Tolerance" class="hph__icon"/>
          </div>
          <h4 class="hph__card-title">Stress Tolerance</h4>
        </div>
        <p class="hph__card-desc text-center">Enhances plant metabolism and overall stress tolerance capabilities</p>
      </div>

    </div>
  </div>
</section>


<!-- ========================
     SECTION 5: Application Methods
======================== -->
<section class="appm__section" id="technology-solution">
  <div class="container">

    <!-- Heading -->
    <div class="row justify-content-center">
      <div class="col-12">
        <h2 class="appm__heading">Application Methods</h2>
        <p class="appm__subtext">Two primary delivery systems for optimal results</p>
      </div>
    </div>

    <!-- 2 Columns -->
    <div class="row g-4 justify-content-center">

      <!-- Foliar Spray -->
      <div class="col-12 col-md-6">
        <div class="text-center">
          <div class="appm__icon-wrap">
            <img src="assets/images/foliar-spray-icon.svg" alt="Foliar Spray" class="appm__icon"/>
          </div>
          <h4 class="appm__col-title">Foliar Spray</h4>
          <p class="appm__col-desc">Applied during vegetative or flowering stages<br>for direct plant surface contact</p>
        </div>
        <div class="appm__info-box">
          <p class="appm__info-title">Best Timing:</p>
          <p class="appm__info-item">• Early morning or late evening</p>
          <p class="appm__info-item">• During vegetative growth</p>
          <p class="appm__info-item">• Pre-flowering stage</p>
        </div>
      </div>

      <!-- Seed Treatment -->
      <div class="col-12 col-md-6">
        <div class="text-center">
          <div class="appm__icon-wrap">
            <img src="assets/images/seed-treatment-icon.svg" alt="Seed Treatment" class="appm__icon"/>
          </div>
          <h4 class="appm__col-title">Seed Treatment</h4>
          <p class="appm__col-desc">Crop-specific treatment for early<br>colonization and establishment</p>
        </div>
        <div class="appm__info-box">
          <p class="appm__info-title">Applications:</p>
          <p class="appm__info-item">• Pre-planting treatment</p>
          <p class="appm__info-item">• Coating application</p>
          <p class="appm__info-item">• Nursery preparation</p>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
