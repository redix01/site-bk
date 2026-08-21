@extends('layouts.bruk')

@section('title', 'About Us – ' . config('app.name', 'Current Financial Bank'))
@section('meta_description', 'Learn why ' . config('app.name', 'Current Financial Bank') . ' was built, how we keep your money secure, and how to open an account in minutes.')
@section('navbar_style', 'on-dark')

@section('content')

    <!-- Breadcrumb Section Start -->
    <div class="breadcrumb-wrap position-relative index-1 bg-title">
        <div class="br-bg br-bg-7 position-absolute top-0 end-0 md-none"></div>
        <img src="{{ asset('bruk/img/breadcrumb/br-shape-2.webp') }}" alt="" class="br-shape-two position-absolute">
        <div class="container position-relative">
            <img src="{{ asset('bruk/img/breadcrumb/br-shape-1.webp') }}" alt="" class="br-shape-one position-absolute md-none">
            <img src="{{ asset('bruk/img/breadcrumb/br-shape-3.webp') }}" alt="" class="br-shape-three position-absolute md-none">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6">
                    <ul class="br-menu list-unstyle d-inline-block">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>About Us</li>
                    </ul>
                    <h2 class="br-title fw-medium text-white mb-0">The Bank Built Around Your Life</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Why Choose Us Start -->
    <div class="wh-area position-relative index-1 pt-130">
        <div class="container">
            <div class="row pb-130 align-items-center">
                <div class="col-lg-6 pe-xxl-1">
                    <div class="wh-img-wrap position-relative">
                        <img src="{{ asset('bruk/img/about/wh-img-shape-1.webp') }}" alt="" class="wh-shape-one position-absolute rotate">
                        <img src="{{ asset('bruk/img/about/wh-img-shape-2.webp') }}" alt="" class="wh-shape-two position-absolute bounce">
                        <img src="{{ asset('bruk/img/about/wh-img-1.webp') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 pe-xxl-0">
                    <div class="wh-content">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Why Choose Us</span>
                            <h2 class="mb-15">Modern Banking, Built On Trust</h2>
                            <p>We built {{ config('app.name', 'Current Financial Bank') }} because everyday banking shouldn't mean long lines, confusing fees, or waiting days for your own money to move. Everything here is designed to be fast, clear, and secure.</p>
                            <p>From your first deposit to your next investment, our team is focused on making banking feel simple again.</p>
                        </div>
                        <a href="{{ route('personal.banking-services') }}" class="link style-four">View Our Products <img src="{{ asset('bruk/img/icons/long-arrow-blue.svg') }}" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center pb-100">
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/shield.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Secure &amp; Insured</h3>
                            <p class="mb-0">Your deposits and data are protected with industry-standard security.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/money-bag.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Transparent Pricing</h3>
                            <p class="mb-0">No hidden fees — you always know what you're paying, and why.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/easy-to-use.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Real Support</h3>
                            <p class="mb-0">Reach a real person by phone, email, or chat, any day of the week.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us End -->

    <!-- Our Story Start -->
    <div class="bg-optional ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Our Story</span>
                <h2 class="mb-0">How {{ config('app.name', 'Current Financial Bank') }} Got Here</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-3 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-20">Launch</h3>
                            <p class="mb-0">{{ config('app.name', 'Current Financial Bank') }} launches with checking and savings accounts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-20">Instant Transfers</h3>
                            <p class="mb-0">Instant transfers and mobile deposits go live.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-20">Investing Added</h3>
                            <p class="mb-0">Investing and crypto deposits added to the app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-20">Growing</h3>
                            <p class="mb-0">A growing number of customers bank with us every day.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Story End -->

    <!-- How It Works Start -->
    <div class="ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Getting Started</span>
                <h2 class="mb-15">Open an Account In Three Steps</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-24">1. Create Your Account</h3>
                            <p>Sign up online in a few minutes — no branch visit required.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-24">2. Verify &amp; Fund</h3>
                            <p>Confirm your identity and make your first deposit securely.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <h3 class="fs-24">3. Start Banking</h3>
                            <p>Transfer, save, and invest right from your dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- How It Works End -->

    <!-- Stats Start -->
    <div class="counter-wrap bg-title ptb-100">
        <div class="container">
            <div class="counter-card-wrap style-three d-flex flex-wrap justify-content-center">
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white"><span class="counter">1</span>K+</h4>
                    <p class="text-offwhite mb-0">Accounts opened</p>
                </div>
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white">$<span class="counter">50</span>M+</h4>
                    <p class="text-offwhite mb-0">Processed in transfers</p>
                </div>
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white">&lt;<span class="counter">10</span>s</h4>
                    <p class="text-offwhite mb-0">Average transfer time</p>
                </div>
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white"><span class="counter">24</span>/7</h4>
                    <p class="text-offwhite mb-0">Support availability</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Stats End -->

    <!-- Testimonial Section Start -->
    <div class="testimonial-wrap style-three position-relative index-1 overflow-hidden ptb-130">
        <div class="container">
            <div class="testimonial-box bg-title round-20">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-cue="slideInUp">
                        <div class="section-title mb-45">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Customer Stories</span>
                            <h2 class="text-white mb-0">What Our Customers Say About Us</h2>
                        </div>
                        <div class="testimonial-slider-three swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testimonial-card style-three">
                                        <img src="{{ asset('bruk/img/icons/square-quote.svg') }}" alt="" class="quote-icon">
                                        <p class="fs-20 f-secondary text-white">Customer testimonials will appear here once collected — replace this placeholder with a real quote before launch.</p>
                                        <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                            <div class="client-info">
                                                <h5 class="fs-20 fw-medium text-white">Pending Review</h5>
                                                <span>Customer testimonial</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="slider-pagination testimonial-pagination d-flex flex-wrap align-items-center"></div>
                        </div>
                    </div>
                    <div class="col-lg-6" data-cue="slideInUp">
                        <div class="testimonial-img">
                            <img src="{{ asset('bruk/img/testimonials/testimonial-img-2.webp') }}" alt="" class="d-block ms-lg-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial Section End -->

    <!-- Contact / Closing CTA Start -->
    <div id="contact" class="bg-optional ptb-130">
        <div class="container text-center">
            <div class="section-title mb-30">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Ready When You Are</span>
                <h2 class="mb-15">Ready to Switch to {{ config('app.name', 'Current Financial Bank') }}?</h2>
                <p class="mb-0">Questions before you open an account? Reach us at <a href="mailto:{{ config('mail.from.address', 'support@example.com') }}">{{ config('mail.from.address', 'support@example.com') }}</a>.</p>
            </div>
            <a href="{{ route('register') }}" class="btn style-one">Open an Account</a>
        </div>
    </div>
    <!-- Contact / Closing CTA End -->

@endsection
