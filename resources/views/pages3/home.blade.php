@extends('layouts.bruk')

@section('title', config('app.name', 'Current Financial Bank') . ' – Digital Banking Made Simple')
@section('meta_description', 'Open an account in minutes, move money instantly, and grow your savings — all from one secure dashboard.')

@section('content')

    <!-- Hero Section Start -->
    <div class="hero-section style-three position-relative index-1">
        <img src="{{ asset('bruk/img/hero/hero-circle-shape.webp') }}" alt="" class="hero-circle-shape position-absolute">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <span class="d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Digital Banking With {{ config('app.name', 'Current Financial Bank') }}</span>
                        <h1 data-cue="slideInUp">Banking That Moves As Fast As You Do</h1>
                        <p class="d-block" data-cue="slideInUp">Open an account in minutes, move money instantly, and grow your savings — all from one secure dashboard. No branch visits, no paperwork, no waiting.</p>
                        <div class="hero-btn" data-animate="bottom" data-cue="slideInUp">
                            <a href="{{ route('register') }}" class="btn style-one">Open an Account</a>
                            <a href="{{ route('about') }}" class="link style-one">See How It Works <img src="{{ asset('bruk/img/icons/arrow-blue.svg') }}" alt=""></a>
                        </div>
                        <div class="our-mission d-flex align-items-center">
                            <div class="mission-left d-flex align-items-center">
                                <img src="{{ asset('bruk/img/hero/target.svg') }}" alt="">
                                <div>
                                    <h6>Bank-grade encryption</h6>
                                    <span>On every transaction</span>
                                </div>
                            </div>
                            <p class="mb-0">Trusted by customers who want their money to move without the wait.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pe-xxl-0">
                    <div class="hero-img-wrap position-relative index-1">
                        <img src="{{ asset('bruk/img/hero/hero-shape-1.webp') }}" alt="" class="hero-shape-one position-absolute end-0 bounce">
                        <img src="{{ asset('bruk/img/hero/hero-shape-2.webp') }}" alt="" class="hero-shape-two position-absolute bottom-0 zoomIn">
                        <img src="{{ asset('bruk/img/hero/hero-img-2.webp') }}" alt="" class="hero-img d-block ms-auto">
                        <img src="{{ asset('bruk/img/hero/card.webp') }}" alt="" class="hero-img-one position-absolute bounce">
                        <img src="{{ asset('bruk/img/hero/hero-img-1.webp') }}" alt="" class="hero-img-two position-absolute moveHorizontal">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- Counter Section Start -->
    <div class="counter-wrap bg-title pt-130 pb-100 pe-xxl-0">
        <div class="container">
            <div class="counter-card-wrap style-three d-flex flex-wrap">
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white"><span class="counter">1</span>K+</h4>
                    <p class="text-offwhite mb-0">Accounts opened by customers who bank with us</p>
                </div>
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white">$<span class="counter">50</span>M+</h4>
                    <p class="text-offwhite mb-0">Processed safely in transfers to date</p>
                </div>
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white">&lt;<span class="counter">10</span>s</h4>
                    <p class="text-offwhite mb-0">Average time for an internal transfer</p>
                </div>
                <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
                    <h4 class="text-white"><span class="counter">24</span>/7</h4>
                    <p class="text-offwhite mb-0">Account access and support, every day</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Counter Section End -->

    <!-- Feature Section Start -->
    <div class="feature-wrap style-two position-relative index-1 overflow-hidden pb-130">
        <div class="container pe-xxl-0">
            <div class="row">
                <div class="col-xl-6 offset-xl-3">
                    <div class="section-title text-center mb-50" data-cue="slideInUp">
                        <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Our Features</span>
                        <h2 class="text-white mb-15">Everything Your Money Needs, In One App</h2>
                        <p class="text-offwhite mb-0">{{ config('app.name', 'Current Financial Bank') }} brings checking, savings, transfers, and investing together — built around security, simplicity, and speed.</p>
                    </div>
                </div>
            </div>
            <div class="feature-box position-relative index-1">
                <div class="row">
                    <div class="col-lg-4" data-cue="slideInUp">
                        <div class="feature-img">
                            <img src="{{ asset('bruk/img/app/app-screen.webp') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="feature-card-wrap">
                            <div class="row justify-content-center">
                                <div class="col-md-6" data-cue="slideInUp">
                                    <div class="feature-card style-two">
                                        <img src="{{ asset('bruk/img/icons/payment.svg') }}" alt="">
                                        <h3 class="fs-24">Secure Payments</h3>
                                        <p class="mb-0">Every transfer is protected with encryption and real-time fraud monitoring.</p>
                                    </div>
                                </div>
                                <div class="col-md-6" data-cue="slideInUp">
                                    <div class="feature-card style-two">
                                        <img src="{{ asset('bruk/img/icons/easy-to-use.svg') }}" alt="">
                                        <h3 class="fs-24">Effortless to Use</h3>
                                        <p class="mb-0">A clean dashboard that puts your balances, transfers, and history one tap away.</p>
                                    </div>
                                </div>
                                <div class="col-md-6" data-cue="slideInUp">
                                    <div class="feature-card style-two">
                                        <img src="{{ asset('bruk/img/icons/shield.svg') }}" alt="">
                                        <h3 class="fs-24">Security First</h3>
                                        <p class="mb-0">Two-factor login, transaction PINs, and session controls keep your account yours.</p>
                                    </div>
                                </div>
                                <div class="col-md-6" data-cue="slideInUp">
                                    <div class="feature-card style-two">
                                        <img src="{{ asset('bruk/img/icons/money-bag.svg') }}" alt="">
                                        <h3 class="fs-24">Low Fees</h3>
                                        <p class="mb-0">Transparent pricing with no hidden charges on everyday banking.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center feature-bottom-link" data-cue="slideInUp">
                                <div class="col-md-6 col-4 ps-xxl-4">
                                    <img src="{{ asset('bruk/img/shape-14.webp') }}" alt="">
                                </div>
                                <div class="col-md-6 col-8 text-end">
                                    <a href="{{ route('register') }}" class="link style-three">Open an Account <img src="{{ asset('bruk/img/icons/arrow-blue.svg') }}" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature Section End -->

    <!-- Moving Text Start -->
    <div class="move-text overflow-hidden" data-cue="slideInUp">
        <ul class="list-unstyle">
            <li>ONLINE BANKING</li>
            <li>MOBILE BANKING</li>
            <li>SECURE TRANSFERS</li>
            <li>SMART SAVINGS</li>
            <li>EASY INVESTING</li>
            <li>ONLINE BANKING</li>
        </ul>
    </div>
    <!-- Moving Text End -->

    <!-- About Section Start -->
    <div class="about-wrap style-three ptb-130">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-title" data-cue="slideInUp">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">About Us</span>
                            <h2 class="fw-semibold mb-15">Banking Built Around You</h2>
                            <p>{{ config('app.name', 'Current Financial Bank') }} was built to make everyday banking simple — open an account online, manage your money from any device, and get support from real people when you need it.</p>
                        </div>
                        <div class="feature-list list-unstyle">
                            <div class="feature-item position-relative" data-cue="slideInUp">
                                <img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 top-0">
                                <h5 class="fs-20 fw-semibold">Instant Transfers</h5>
                                <p class="mb-0">Send money between accounts or to other banks in seconds, not days.</p>
                            </div>
                            <div class="feature-item position-relative" data-cue="slideInUp">
                                <img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 top-0">
                                <h5 class="fs-20 fw-semibold">Smart Savings Tools</h5>
                                <p class="mb-0">Set savings goals and track your progress automatically.</p>
                            </div>
                            <div class="feature-item position-relative" data-cue="slideInUp">
                                <img src="{{ asset('bruk/img/icons/check.svg') }}" alt="">
                                <h5>No Hidden Fees</h5>
                                <p class="mb-0">Clear pricing on every account, every time.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ps-xxl-13">
                    <div class="about-img-wrap position-relative" data-cue="slideInUp">
                        <img src="{{ asset('bruk/img/about/about-img-2.webp') }}" alt="" class="about-img">
                        <img src="{{ asset('bruk/img/about/card-2.webp') }}" alt="" class="credit-card position-absolute bounce">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Section End -->

    <!-- Product Highlight Section Start -->
    <div class="simple-wrap pt-130">
        <div class="container">
            <div class="row align-items-center pb-130 gx-xl-25">
                <div class="col-lg-6" data-cue="slideInUp">
                    <div class="simple-img style-one">
                        <img src="{{ asset('bruk/img/about/simple-img-1.webp') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 pe-xxl-0" data-cue="slideInUp">
                    <div class="simple-content style-one position-relative">
                        <img src="{{ asset('bruk/img/shape-5.webp') }}" alt="" class="simple-shape-one position-absolute rotate">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Secure Transactions</span>
                            <h2 class="mb-15">Move Money With Confidence</h2>
                            <p>Every transfer — internal, wire, or crypto — runs through real-time verification and fraud checks, so your money moves safely every time.</p>
                            <p>Track the status of every transaction from initiated to completed, with instant notifications along the way.</p>
                        </div>
                        <a href="{{ route('transfer') }}" class="btn style-one">Start a Transfer</a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center pb-130 gx-xl-25">
                <div class="col-lg-6 order-lg-1 order-2" data-cue="slideInUp">
                    <div class="simple-content style-two position-relative">
                        <img src="{{ asset('bruk/img/shape-11.webp') }}" alt="" class="simple-shape-two position-absolute">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Your Accounts</span>
                            <h2 class="mb-15">One Dashboard, Every Account</h2>
                            <p>See your checking, savings, and investment balances in one place — updated in real time, no refreshing required.</p>
                            <p>Download statements, review past transactions, and manage your profile and security settings without calling support.</p>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn style-one">View Your Dashboard</a>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-2 order-1 pe-xxl-0" data-cue="slideInUp">
                    <div class="simple-img style-two">
                        <img src="{{ asset('bruk/img/about/simple-img-2.webp') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Highlight Section End -->

    <!-- Account Types Section Start -->
    <div class="bg-optional pt-130 pb-100">
        <div class="container">
            <div class="section-title text-center mb-45">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Account Types</span>
                <h2 class="mb-0">Choose the Account That Fits</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="pricing-card position-relative transition index-1 mb-30">
                        <h5 class="fw-semibold transition">Everyday Checking</h5>
                        <p class="transition">Free everyday spending account with instant transfers and no minimum balance.</p>
                        <h6 class="fs-16 f-primary transition">What's included?</h6>
                        <ul class="pricing-features list-unstyle">
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">No monthly fees</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Instant transfers</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Mobile deposits</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Debit access</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn style-one d-block w-100">Open This Account</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="pricing-card featured position-relative transition index-1 mb-30">
                        <span class="fs-15 fw-semibold text-title d-block mb-10">Most Popular</span>
                        <h5 class="fw-semibold transition">High-Yield Savings</h5>
                        <p class="transition">Grow your balance automatically with a competitive rate and goal-based savings tools.</p>
                        <h6 class="fs-16 f-primary transition">What's included?</h6>
                        <ul class="pricing-features list-unstyle">
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Competitive APY</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Automatic round-ups</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Savings goals</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">No lock-in</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn style-one d-block w-100">Open This Account</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="pricing-card position-relative transition index-1 mb-30">
                        <h5 class="fw-semibold transition">Investing</h5>
                        <p class="transition">Put your money to work with a guided, low-fee investing account.</p>
                        <h6 class="fs-16 f-primary transition">What's included?</h6>
                        <ul class="pricing-features list-unstyle">
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Fractional investing</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Portfolio tracking</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Low fees</li>
                            <li class="position-relative transition"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 transition">Flexible withdrawals</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn style-one d-block w-100">Open This Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Account Types Section End -->

    <!-- Testimonial Section Start -->
    <div class="testimonial-wrap style-three position-relative index-1 overflow-hidden">
        <div class="container">
            <div class="testimonial-box bg-title round-20">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-cue="slideInUp">
                        <div class="section-title mb-45">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Customer Stories</span>
                            <h2 class="text-white mb-0">What Our Customers Say About Banking With Us</h2>
                        </div>
                        <div class="testimonial-slider-three swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testimonial-card style-three">
                                        <img src="{{ asset('bruk/img/icons/square-quote.svg') }}" alt="" class="quote-icon">
                                        <p class="fs-20 f-secondary text-white">Switching my account over took less than ten minutes, and I've never had a transfer take longer than a few seconds since. It's the first bank app I've actually enjoyed using.</p>
                                        <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                            <div class="client-img rounded-circle">
                                                <img src="{{ asset('bruk/img/testimonials/client-4.webp') }}" alt="" class="rounded-circle">
                                            </div>
                                            <div class="client-info">
                                                <h5 class="fs-20 fw-medium text-white">Maria Chen</h5>
                                                <span>Small Business Owner</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-card style-three">
                                        <img src="{{ asset('bruk/img/icons/square-quote.svg') }}" alt="" class="quote-icon">
                                        <p class="fs-20 f-secondary text-white">The automatic savings round-ups finally got me to stick with a savings goal. Being able to see everything — checking, savings, transfers — in one dashboard makes a real difference.</p>
                                        <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                            <div class="client-img rounded-circle">
                                                <img src="{{ asset('bruk/img/testimonials/client-1.webp') }}" alt="" class="rounded-circle">
                                            </div>
                                            <div class="client-info">
                                                <h5 class="fs-20 fw-medium text-white">James Okafor</h5>
                                                <span>Freelance Designer</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-card style-three">
                                        <img src="{{ asset('bruk/img/icons/square-quote.svg') }}" alt="" class="quote-icon">
                                        <p class="fs-20 f-secondary text-white">I had a question about a wire transfer and support picked up in under two minutes. That kind of responsiveness is rare from an online-only bank.</p>
                                        <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                            <div class="client-img rounded-circle">
                                                <img src="{{ asset('bruk/img/testimonials/client-2.webp') }}" alt="" class="rounded-circle">
                                            </div>
                                            <div class="client-info">
                                                <h5 class="fs-20 fw-medium text-white">Priya Sharma</h5>
                                                <span>Marketing Manager</span>
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

    <!-- Moving Text Start -->
    <div class="move-text overflow-hidden mtb-130" data-cue="slideInUp">
        <ul class="list-unstyle">
            <li>BANK ON THE GO</li>
            <li>BANK ON THE GO</li>
            <li>BANK ON THE GO</li>
            <li>BANK ON THE GO</li>
            <li>BANK ON THE GO</li>
            <li>BANK ON THE GO</li>
        </ul>
    </div>
    <!-- Moving Text End -->

    <!-- App Section Start -->
    <div class="container">
        <div class="app-box bg-optional">
            <div class="row align-items-lg-end">
                <div class="col-lg-6" data-cue="slideInUp">
                    <div class="app-img">
                        <img src="{{ asset('bruk/img/hero/hero-img-4.webp') }}" alt="" class="round-2">
                    </div>
                </div>
                <div class="col-lg-6" data-cue="slideInUp">
                    <div class="app-content">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Coming Soon</span>
                            <h2>Take {{ config('app.name', 'Current Financial Bank') }} Wherever You Go</h2>
                        </div>
                        <ul class="feature-list list-unstyle">
                            <li class="position-relative fw-bold"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0">Check balances and move money from your phone</li>
                            <li class="position-relative fw-bold"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0">Get instant alerts on every transaction</li>
                            <li class="position-relative fw-bold"><img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0">Message support directly from the app</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- App Section End -->

    <!-- Help & Security Section Start -->
    <div class="blog-wrap style-three position-relative index-1 pt-130 pb-100">
        <div class="container position-relative">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-8 offset-md-2">
                    <div class="section-title text-center mb-45" data-cue="slideInUp">
                        <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Help &amp; Security</span>
                        <h2 class="mb-0">Everything You Need to Bank Safely</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6">
                    <div class="blog-card style-three position-relative mb-30" data-cue="slideInUp">
                        <div class="blog-info">
                            <h3><a href="{{ route('personal.customer-support') }}">Help Center</a></h3>
                        </div>
                        <p class="mb-0">Answers to common questions about accounts, transfers, and fees.</p>
                        <a href="{{ route('personal.customer-support') }}" class="link style-one">View More Details <img src="{{ asset('bruk/img/icons/long-arrow-blue.svg') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="blog-card style-three position-relative mb-30" data-cue="slideInUp">
                        <div class="blog-info">
                            <h3><a href="{{ route('personal.banking-services') }}">Account Security</a></h3>
                        </div>
                        <p class="mb-0">Two-factor login, transaction PINs, and session controls on every account.</p>
                        <a href="{{ route('personal.banking-services') }}" class="link style-one">View More Details <img src="{{ asset('bruk/img/icons/long-arrow-blue.svg') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="blog-card style-three position-relative mb-30" data-cue="slideInUp">
                        <div class="blog-info">
                            <h3><a href="{{ route('personal.customer-support') }}">Open an Account</a></h3>
                        </div>
                        <p class="mb-0">Sign up online in a few minutes — no branch visit required.</p>
                        <a href="{{ route('register') }}" class="link style-one">Get Started <img src="{{ asset('bruk/img/icons/long-arrow-blue.svg') }}" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Help & Security Section End -->

@endsection
