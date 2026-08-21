@extends('layouts.bruk')

@section('title', 'Banking Services – ' . config('app.name', 'Current Financial Bank'))
@section('meta_description', 'Compare ' . config('app.name', 'Current Financial Bank') . '\'s personal checking accounts and see the benefits every account holder gets.')
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
                        <li>Banking Services</li>
                    </ul>
                    <h2 class="br-title fw-medium text-white mb-0">Banking Services Designed Around Your Life</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Intro Start -->
    <div class="ptb-130">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="section-title mb-30">
                        <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Everyday Banking</span>
                        <h2 class="mb-15">Everyday Banking Without the Friction</h2>
                        <p class="mb-0">Pick the checking experience that fits your lifestyle. From fee-free essentials to accounts that reward higher balances, {{ config('app.name', 'Current Financial Bank') }} keeps your everyday money management simple and secure.</p>
                    </div>
                    <a href="{{ route('register') }}" class="btn style-one">Start With {{ config('app.name', 'Current Financial Bank') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Intro End -->

    <!-- Checking Options Start -->
    <div class="bg-optional ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Personal Checking Options</span>
                <h2 class="mb-0">Choose the Account That Keeps Pace With You</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="pricing-card position-relative transition index-1 mb-30">
                        <h5 class="fw-semibold transition">Basic Checking</h5>
                        <p class="transition">No minimum opening balance and no monthly service charge with direct deposit. Includes free eStatements and digital banking tools for everyday spending.</p>
                        <a href="{{ route('register') }}" class="btn style-one d-block w-100">Open This Account</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="pricing-card featured position-relative transition index-1 mb-30">
                        <span class="fs-15 fw-semibold text-title d-block mb-10">Most Popular</span>
                        <h5 class="fw-semibold transition">Interest Checking</h5>
                        <p class="transition">Earn competitive interest as your balance grows. Enjoy the same no-minimum opening balance, free eStatements, and access to online banking and bill pay.</p>
                        <a href="{{ route('register') }}" class="btn style-one d-block w-100">Open This Account</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="pricing-card position-relative transition index-1 mb-30">
                        <h5 class="fw-semibold transition">55+ Interest Checking</h5>
                        <p class="transition">Designed for clients 55 and over with no monthly service charge, no balance requirements, free standard checks, and secure digital tools included.</p>
                        <a href="{{ route('register') }}" class="btn style-one d-block w-100">Open This Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Checking Options End -->

    <!-- One Account Start -->
    <div class="ptb-130">
        <div class="container">
            <div class="row align-items-center gx-xl-25">
                <div class="col-lg-6" data-cue="slideInUp">
                    <div class="simple-img style-one">
                        <img src="{{ asset('bruk/img/about/simple-img-2.webp') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 pe-xxl-0" data-cue="slideInUp">
                    <div class="simple-content style-one position-relative">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Savings, Built In</span>
                            <h2 class="mb-15">One Account, Multiple Pockets</h2>
                            <p>Separate your spending and savings without opening extra products. Move money between your main balance and savings pocket instantly with unlimited transfers.</p>
                            <p>Schedule automatic savings contributions on your own schedule, withdraw whenever you need, and keep everything organized inside one secure dashboard.</p>
                        </div>
                        <a href="{{ route('personal.customer-support') }}" class="btn style-one">Talk to a Specialist</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- One Account End -->

    <!-- Benefits Start -->
    <div class="bg-optional ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Benefits</span>
                <h2 class="mb-15">Benefits for Account Holders</h2>
                <p class="mb-0">{{ config('app.name', 'Current Financial Bank') }} customers enjoy layered security, real-time awareness, and tools that put them in control around the clock.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/money-bag.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Competitive Rates</h3>
                            <p class="mb-0">Grow balances faster with competitive rates and tools that help you maximize every deposit.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/payment.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Smart Alerts</h3>
                            <p class="mb-0">Get text and email notifications for large deposits, withdrawals, and card purchases so you can respond instantly.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/shield.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Security First</h3>
                            <p class="mb-0">Chip-enabled debit cards, fraud monitoring, and international access keep your funds protected at home and abroad.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Benefits End -->

    <!-- Closing CTA Start -->
    <div class="ptb-130">
        <div class="container text-center">
            <div class="section-title mb-30">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Ready When You Are</span>
                <h2 class="mb-15">Open an Account in Minutes</h2>
                <p class="mb-0">Set up digital access, choose the right checking option, and start banking with confidence today.</p>
            </div>
            <a href="{{ route('register') }}" class="btn style-one me-3">Get Started</a>
            <a href="{{ route('personal.customer-support') }}" class="link style-one">Request a Call <img src="{{ asset('bruk/img/icons/arrow-blue.svg') }}" alt=""></a>
        </div>
    </div>
    <!-- Closing CTA End -->

@endsection
