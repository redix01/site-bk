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
                        <h1 class="mil-mb-60">Customer Support &amp; Assistance</h1>
                        <ul class="mil-breadcrumbs mil-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('personal.customer-support') }}">Customer Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- intro -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">Support That Travels With You</h2>
                    <p class="mil-text-m mil-soft mil-mb-30 mil-up">
                        Whether you bank online, in-app, or in person, our specialists are ready from 9am to 5pm daily—including holidays—to resolve questions and keep your finances moving.
                    </p>
                    <div class="mil-up">
                        <a href="mailto:support@nestopro.com" class="mil-btn mil-m mil-add-arrow">Email Support</a>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="{{ asset('img/inner-pages/8.png') }}" alt="Customer support team" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- intro end -->

    <!-- channels -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-100">
                <div class="row justify-content-center mil-text-center">
                    <div class="col-xl-8 mil-mb-80-adaptive-30">
                        <h2 class="mil-up">Choose the Channel That Fits</h2>
                        <p class="mil-text-m mil-soft mil-up">
                            Reach us through dedicated phone lines, secure messaging, or instant chat inside mobile banking.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/9.svg') }}" alt="Phone icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Call Us</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                +44 20 7946 0123 <br>
                                9:00–17:00 (Sun &amp; Holidays included)
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/10.svg') }}" alt="Message icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Secure Messages</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                Use our Amiga virtual assistant, website, or mobile app for unlimited conversations and real-time guidance.
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/11.svg') }}" alt="Branch icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Visit a Branch</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                25 Kingsway Street, Canary Wharf, London, E14 5HP, United Kingdom. Schedule ahead or walk in for tailored support.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- channels end -->

    <!-- alerts -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row flex-sm-row-reverse justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">Stay Alert, Stay Secure</h2>
                    <p class="mil-text-m mil-soft mil-mb-15 mil-up">
                        Receive automatic notifications for deposits or withdrawals at $50,000 and above, plus card purchases between $100,000 and $200,000.
                    </p>
                    <p class="mil-text-m mil-soft mil-mb-30 mil-up">
                        Customize alert thresholds, delivery channels, and escalation contacts so every transaction meets your peace-of-mind standards.
                    </p>
                    <div class="mil-up">
                        <a href="{{ route('personal.open-account') }}" class="mil-btn mil-m mil-add-arrow">Manage Alerts</a>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="{{ asset('img/inner-pages/9.png') }}" alt="Security alerts illustration" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- alerts end -->

    <!-- faq -->
    <div class="mil-dark-2 mil-p-160-130">
        <div class="container">
            <div class="mil-text-center mil-mb-60">
                <h2 class="mil-light mil-mb-20 mil-up">Quick Answers</h2>
                <p class="mil-text-l mil-pale-2 mil-up">Our team resolves most support requests in under 15 minutes.</p>
            </div>
            <div class="row">
                <div class="col-xl-6 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">How do I reset my digital banking access?</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Use the “Forgot login” option in-app or online. We’ll send a secure code via SMS or email to help you regain access immediately.
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">Can I speak with a financial advisor?</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Yes. Request a callback or schedule a virtual meeting to explore savings plans, lockers, international cards, and more.
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">Do you offer multilingual support?</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Our customer success team provides assistance across multiple languages to serve households, businesses, and communities worldwide.
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">How do I report card fraud?</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Call our hotline immediately or use the mobile app to lock your card. Our specialists will guide you through dispute resolution and issue a replacement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- faq end -->

    <!-- final cta -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-160" style="background-image: url({{ asset('img/home-4/5.png') }})">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xl-7 mil-sm-text-center">
                        <h2 class="mil-light mil-mb-30 mil-up">We’re Here Whenever You Need Us</h2>
                        <p class="mil-text-m mil-mb-60 mil-light mil-up">
                            From account alerts to international travel advice, our advisors are just a message away.
                        </p>
                        <div class="mil-buttons-frame mil-up">
                            <a href="tel:+442079460123" class="mil-btn mil-md">Call Support</a>
                            <a href="{{ route('personal.open-account') }}" class="mil-btn mil-border mil-md">Open an Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- final cta end -->

</div>
@endsection

