@extends('pages.layout.app_layout')

@section('content')
<div id="smooth-content">

    <!-- banner -->
    <div class="mil-banner mil-banner-inner mil-dissolve">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-8">
                    <div class="mil-banner-text mil-text-center">
                        <div class="mil-text-m mil-mb-20">Personal Banking</div>
                        <h1 class="mil-mb-60">Open a Banko Account</h1>
                        <ul class="mil-breadcrumbs mil-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('personal.open-account') }}">Open an Account</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- steps -->
    <div class="mil-dark-2 mil-p-160-130">
        <div class="container">
            <div class="mil-text-center mil-mb-60">
                <h2 class="mil-light mil-mb-20 mil-up">Three Steps to Get Started</h2>
                <p class="mil-text-l mil-pale-2 mil-up">Apply completely online, verify your details, and activate the benefits that keep your money organized.</p>
            </div>
            <div class="row">
                <div class="col-xl-4 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                        <span class="mil-text-s mil-accent mil-mb-20 mil-up">STEP 01</span>
                        <h5 class="mil-mb-20 mil-light mil-up">Share Your Details</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Complete the guided application with your personal information and direct deposit preferences. No minimum opening balance required.
                        </p>
                    </div>
                </div>
                <div class="col-xl-4 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                        <span class="mil-text-s mil-accent mil-mb-20 mil-up">STEP 02</span>
                        <h5 class="mil-mb-20 mil-light mil-up">Verify Securely</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Complete digital KYC in minutes. We protect your data with layered authentication and notify you of any activity above $50,000.
                        </p>
                    </div>
                </div>
                <div class="col-xl-4 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                        <span class="mil-text-s mil-accent mil-mb-20 mil-up">STEP 03</span>
                        <h5 class="mil-mb-20 mil-light mil-up">Activate Your Account</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Set your savings pocket, enroll in alerts, and start transacting with your chip debit card and secure mobile banking access.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- steps end -->

    <!-- requirements -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">What You Need Before You Apply</h2>
                    <ul class="mil-list-1 mil-accent">
                        <li>
                            <div class="mil-up">
                                <h5 class="mil-mb-15 mil-light mil-up">Valid Identification</h5>
                                <p class="mil-text-m mil-soft mil-up">Government-issued ID and proof of address to complete the digital KYC process.</p>
                            </div>
                        </li>
                        <li>
                            <div class="mil-up">
                                <h5 class="mil-mb-15 mil-light mil-up">Funding Source</h5>
                                <p class="mil-text-m mil-soft mil-up">Connect your existing bank or employer payroll for instant funding and auto-deposit setup.</p>
                            </div>
                        </li>
                        <li>
                            <div class="mil-up">
                                <h5 class="mil-mb-15 mil-light mil-up">Contact Preferences</h5>
                                <p class="mil-text-m mil-soft mil-up">Choose SMS or email for account alerts, including large transactions and savings milestones.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="{{ asset('img/inner-pages/6.png') }}" alt="Account onboarding illustration" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- requirements end -->

    <!-- automation -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-100">
                <div class="row justify-content-center mil-text-center">
                    <div class="col-xl-8 mil-mb-80-adaptive-30">
                        <h2 class="mil-up">Automate Savings from Day One</h2>
                        <p class="mil-text-m mil-soft mil-up">
                            Set recurring transfers to your savings pocket weekly, fortnightly, or monthly. Adjust anytime and access funds instantly when plans change.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/7.svg') }}" alt="Scheduling icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Flexible Scheduling</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                Create multiple automated contributions and edit them with one tap inside online or mobile banking.
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/8.svg') }}" alt="Visibility icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Real-Time Visibility</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                Track progress toward each goal with dashboards that show balance growth, upcoming transfers, and recent activity.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- automation end -->

    <!-- support -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row flex-sm-row-reverse justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">Help Whenever You Need It</h2>
                    <p class="mil-text-m mil-soft mil-mb-30 mil-up">
                        Unlimited consultations through our website, mobile banking, and dedicated support line ensure you always have answers to account questions.
                    </p>
                    <div class="mil-up">
                        <a href="{{ route('personal.customer-support') }}" class="mil-btn mil-m mil-add-arrow">Connect with Support</a>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="{{ asset('img/inner-pages/7.png') }}" alt="Customer support illustration" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- support end -->

    <!-- final cta -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-160" style="background-image: url({{ asset('img/home-5/5.png') }})">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xl-7 mil-sm-text-center">
                        <h2 class="mil-light mil-mb-30 mil-up">Ready to Open Your Account?</h2>
                        <p class="mil-text-m mil-mb-60 mil-light mil-up">
                            Secure your spot with Banko and unlock digital tools, interest-earning accounts, and 24/7 account alerts.
                        </p>
                        <div class="mil-buttons-frame mil-up">
                            <a href="{{ route('register') }}" class="mil-btn mil-md">Apply Now</a>
                            <a href="{{ route('personal.banking-services') }}" class="mil-btn mil-border mil-md">Compare Accounts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- final cta end -->

</div>
@endsection

