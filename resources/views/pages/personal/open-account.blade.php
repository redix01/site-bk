@extends('layouts.bruk')

@section('title', 'Open an Account – ' . config('app.name', 'Current Financial Bank'))
@section('meta_description', 'Open a ' . config('app.name', 'Current Financial Bank') . ' account online in three steps — apply, verify, and activate your benefits.')
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
                        <li>Open an Account</li>
                    </ul>
                    <h2 class="br-title fw-medium text-white mb-0">Open a {{ config('app.name', 'Current Financial Bank') }} Account</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Steps Start -->
    <div class="ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Getting Started</span>
                <h2 class="mb-15">Three Steps to Get Started</h2>
                <p class="mb-0">Apply completely online, verify your details, and activate the benefits that keep your money organized.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <span class="section-subtitle d-block fs-15 fw-semibold text-title mb-10">STEP 01</span>
                            <h3 class="fs-24">Share Your Details</h3>
                            <p>Complete the guided application with your personal information and direct deposit preferences. No minimum opening balance required.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <span class="section-subtitle d-block fs-15 fw-semibold text-title mb-10">STEP 02</span>
                            <h3 class="fs-24">Verify Securely</h3>
                            <p>Complete digital identity verification in minutes. We protect your data with layered authentication and monitor for unusual activity.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-cue="slideInUp">
                    <div class="feature-card style-one mb-30 transition text-center">
                        <div class="feature-text">
                            <span class="section-subtitle d-block fs-15 fw-semibold text-title mb-10">STEP 03</span>
                            <h3 class="fs-24">Activate Your Account</h3>
                            <p>Set your savings pocket, enroll in alerts, and start transacting with your debit card and secure mobile banking access.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Steps End -->

    <!-- What You Need Start -->
    <div class="bg-optional ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Before You Apply</span>
                <h2 class="mb-0">What You Need Before You Apply</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/shield.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Valid Identification</h3>
                            <p class="mb-0">Government-issued ID and proof of address to complete the digital verification process.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/money-bag.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Funding Source</h3>
                            <p class="mb-0">Connect your existing bank or employer payroll for instant funding and auto-deposit setup.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/easy-to-use.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Contact Preferences</h3>
                            <p class="mb-0">Choose SMS or email for account alerts, including large transactions and savings milestones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- What You Need End -->

    <!-- Automate Savings Start -->
    <div class="ptb-130">
        <div class="container">
            <div class="row align-items-center gx-xl-25">
                <div class="col-lg-6" data-cue="slideInUp">
                    <div class="simple-img style-one">
                        <img src="{{ asset('bruk/img/about/about-img-1.webp') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 pe-xxl-0" data-cue="slideInUp">
                    <div class="simple-content style-one position-relative">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Savings Goals</span>
                            <h2 class="mb-15">Automate Savings From Day One</h2>
                            <p>Set recurring transfers to your savings pocket weekly, fortnightly, or monthly. Adjust anytime and access funds instantly when plans change.</p>
                        </div>
                        <div class="feature-list list-unstyle">
                            <div class="feature-item position-relative" data-cue="slideInUp">
                                <img src="{{ asset('bruk/img/icons/check.svg') }}" alt="" class="position-absolute start-0 top-0">
                                <h5 class="fs-20 fw-semibold">Flexible Scheduling</h5>
                                <p class="mb-0">Create multiple automated contributions and edit them with one tap inside online or mobile banking.</p>
                            </div>
                            <div class="feature-item position-relative" data-cue="slideInUp">
                                <img src="{{ asset('bruk/img/icons/check.svg') }}" alt="">
                                <h5>Real-Time Visibility</h5>
                                <p class="mb-0">Track progress toward each goal with dashboards that show balance growth, upcoming transfers, and recent activity.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Automate Savings End -->

    <!-- Help Start -->
    <div class="bg-optional ptb-130">
        <div class="container text-center">
            <div class="section-title mb-30">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Support</span>
                <h2 class="mb-15">Help Whenever You Need It</h2>
                <p class="mb-0">Unlimited consultations through our website, mobile banking, and dedicated support line ensure you always have answers to account questions.</p>
            </div>
            <a href="{{ route('personal.customer-support') }}" class="btn style-one">Connect With Support</a>
        </div>
    </div>
    <!-- Help End -->

    <!-- Closing CTA Start -->
    <div class="ptb-130">
        <div class="container text-center">
            <div class="section-title mb-30">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Ready When You Are</span>
                <h2 class="mb-15">Ready to Open Your Account?</h2>
                <p class="mb-0">Secure your spot with {{ config('app.name', 'Current Financial Bank') }} and unlock digital tools, interest-earning accounts, and 24/7 account alerts.</p>
            </div>
            <a href="{{ route('register') }}" class="btn style-one me-3">Apply Now</a>
            <a href="{{ route('personal.banking-services') }}" class="link style-one">Compare Accounts <img src="{{ asset('bruk/img/icons/arrow-blue.svg') }}" alt=""></a>
        </div>
    </div>
    <!-- Closing CTA End -->

@endsection
