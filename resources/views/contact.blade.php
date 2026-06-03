@extends('layout.frontlayout')
@section('title', 'Contact Us – Bharat Biomer')
@section('seo_description', 'Contact Bharat Biomer for sustainable agri-biotechnology solutions, product support, and partnership inquiries.')
@section('seo_keywords', 'contact us, Bharat Biomer, sustainable agriculture inquiry, biotech support, agri partnership')

@section('content')

  <!-- ========================
       SECTION 1: Hero - Contact Us
  ======================== -->
  <section class="conh__section">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-8">
          <h1 class="conh__heading">Contact Us</h1>
          <p class="conh__desc">Let's Build Sustainable Agriculture Together</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================
     SECTION 2: Contact Info + Form
======================== -->
<section class="conif__section">
  <div class="container">
    <div class="row g-5">

      <!-- Left: Contact Info Blocks -->
      <div class="col-12 col-lg-6">
        <div class="d-flex flex-column gap-4">

         <!-- Location -->
<a href="https://www.google.com/maps/search/India" target="_blank" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/location-icon.svg" alt="Location" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Location</h5>
    <p class="conif__info-desc">India</p>
  </div>
</a>

<!-- Phone -->
<a href="tel:+917828333334" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/phone-icon.svg" alt="Phone" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Phone</h5>
    <p class="conif__info-desc">+91 78283 33334</p>
  </div>
</a>

<!-- Email -->
<a href="javascript:void(0)" onclick="window.open('https://mail.google.com/mail/?view=cm&to=admin@bharatbiomer.com', '_blank')" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/email-icon.svg" alt="Email" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Email</h5>
    <p class="conif__info-desc">admin@bharatbiomer.com</p>
  </div>
</a>

<!-- Website -->
<a href="https://www.bharatbiomer.com" target="_blank" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/website-icon.svg" alt="Website" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Website</h5>
    <p class="conif__info-desc">www.bharatbiomer.com</p>
  </div>
</a>

        </div>
      </div>

      <!-- Right: Contact Form -->
      <div class="col-12 col-lg-6">
        <div class="conif__form-card">
          <h3 class="conif__form-title">Send us a Message</h3>

          <div class="conif__field-wrap">
            <label class="conif__label">Full Name</label>
            <input type="text" class="conif__input" placeholder="Enter your name"/>
          </div>

          <div class="conif__field-wrap">
            <label class="conif__label">Email Address</label>
            <input type="email" class="conif__input" placeholder="Enter your email"/>
          </div>

          <div class="conif__field-wrap">
            <label class="conif__label">Phone Number</label>
            <input type="tel" class="conif__input" placeholder="Enter your phone"/>
          </div>

          <div class="conif__field-wrap">
            <label class="conif__label">Message</label>
            <textarea class="conif__textarea" placeholder="Write your message here..."></textarea>
          </div>

          <button class="conif__submit-btn">Send Message</button>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ========================
     SECTION 3: Map Location
======================== -->
<section class="conmap__section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="conmap__map-box">
  <iframe 
    src="https://www.google.com/maps/embed?pb=YOUR_LOCATION_EMBED_URL" 
    width="100%" 
    height="500" 
    style="border:0; border-radius:8px;" 
    allowfullscreen="" 
    loading="lazy">
  </iframe>
</div>
      </div>
    </div>
  </div>
</section>

@endsection