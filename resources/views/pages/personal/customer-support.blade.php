@extends('layouts.bruk')

@section('title', 'Customer Support – ' . config('app.name', 'Current Financial Bank'))
@section('meta_description', 'Reach ' . config('app.name', 'Current Financial Bank') . ' support by phone, secure message, or in branch — plus quick answers to common account questions.')
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
                        <li>Customer Support</li>
                    </ul>
                    <h2 class="br-title fw-medium text-white mb-0">Customer Support &amp; Assistance</h2>
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
                        <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Customer Support</span>
                        <h2 class="mb-15">Support That Travels With You</h2>
                        <p class="mb-0">Whether you bank online, in-app, or in person, our specialists are ready to resolve questions and keep your finances moving.</p>
                    </div>
                    <a href="mailto:{{ config('mail.from.address', 'support@example.com') }}" class="btn style-one">Email Support</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Intro End -->

    <!-- Channels Start -->
    <div class="bg-optional ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Get In Touch</span>
                <h2 class="mb-15">Choose the Channel That Fits</h2>
                <p class="mb-0">Reach us through dedicated phone lines, secure messaging, or instant chat inside mobile banking.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/payment.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Call Us</h3>
                            <p class="mb-0">+44 20 7946 0123<br>9:00–17:00 (Sun &amp; Holidays included)</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/easy-to-use.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Secure Messages</h3>
                            <p class="mb-0">Message us through the website or mobile app for unlimited conversations and real-time guidance.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4" data-cue="slideInUp">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition">
                        <div class="feature-icon bg-white position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="{{ asset('bruk/img/icons/shield.svg') }}" alt="" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Visit a Branch</h3>
                            <p class="mb-0">25 Kingsway Street, Canary Wharf, London, E14 5HP, United Kingdom. Schedule ahead or walk in for tailored support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Channels End -->

    <!-- Alerts Start -->
    <div class="ptb-130">
        <div class="container">
            <div class="row align-items-center gx-xl-25">
                <div class="col-lg-6" data-cue="slideInUp">
                    <div class="simple-img style-one">
                        <img src="{{ asset('bruk/img/about/simple-img-1.webp') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 pe-xxl-0" data-cue="slideInUp">
                    <div class="simple-content style-one position-relative">
                        <div class="section-title">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Account Alerts</span>
                            <h2 class="mb-15">Stay Alert, Stay Secure</h2>
                            <p>Receive automatic notifications for large deposits or withdrawals and unusual card activity, so nothing on your account happens without you knowing.</p>
                            <p>Customize alert thresholds, delivery channels, and escalation contacts so every transaction meets your peace-of-mind standards.</p>
                        </div>
                        <a href="{{ route('personal.banking-services') }}" class="btn style-one">Manage Alerts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Alerts End -->

    <!-- FAQ Start -->
    <div class="bg-optional ptb-130">
        <div class="container">
            <div class="section-title text-center mb-50">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title" data-cue="slideInUp">Quick Answers</span>
                <h2 class="mb-0">Our Team Resolves Most Requests in Under 15 Minutes</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion" id="supportFaq">
                        <div class="accordion-item mb-20">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How do I reset my digital banking access?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#supportFaq">
                                <div class="accordion-body">Use the "Forgot login" option in-app or online. We'll send a secure code via SMS or email to help you regain access immediately.</div>
                            </div>
                        </div>
                        <div class="accordion-item mb-20">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I speak with a financial advisor?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#supportFaq">
                                <div class="accordion-body">Yes. Request a callback or schedule a virtual meeting to explore savings plans, international cards, and more.</div>
                            </div>
                        </div>
                        <div class="accordion-item mb-20">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Do you offer multilingual support?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#supportFaq">
                                <div class="accordion-body">Our customer success team provides assistance across multiple languages to serve households, businesses, and communities worldwide.</div>
                            </div>
                        </div>
                        <div class="accordion-item mb-20">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    How do I report card fraud?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#supportFaq">
                                <div class="accordion-body">Call our hotline immediately or use the mobile app to lock your card. Our specialists will guide you through dispute resolution and issue a replacement.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ End -->

    <!-- Closing CTA Start -->
    <div class="ptb-130">
        <div class="container text-center">
            <div class="section-title mb-30">
                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">We're Here For You</span>
                <h2 class="mb-15">We're Here Whenever You Need Us</h2>
                <p class="mb-0">From account alerts to travel advice, our advisors are just a message away.</p>
            </div>
            <a href="tel:442079460123" class="btn style-one me-3">Call Support</a>
            <a href="{{ route('register') }}" class="link style-one">Open an Account <img src="{{ asset('bruk/img/icons/arrow-blue.svg') }}" alt=""></a>
        </div>
    </div>
    <!-- Closing CTA End -->

@endsection
