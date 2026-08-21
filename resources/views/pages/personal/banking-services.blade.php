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
                        <h1 class="mil-mb-60">Banking Services Designed Around Your Life</h1>
                        <ul class="mil-breadcrumbs mil-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('personal.banking-services') }}">Banking Services</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- hero -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">Everyday Banking Without the Friction</h2>
                    <p class="mil-text-m mil-soft mil-mb-30 mil-up">
                        Pick the checking experience that fits your lifestyle. From fee-free essentials to accounts that reward higher balances, Banko keeps your everyday money management simple and secure.
                    </p>
                    <div class="mil-up">
                        <a href="{{ route('personal.open-account') }}" class="mil-btn mil-m mil-add-arrow">Start with Banko</a>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="{{ asset('img/inner-pages/3.png') }}" alt="Personal banking illustration" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- hero end -->

    <!-- account types -->
    <div class="mil-dark-2 mil-p-160-130">
        <div class="container">
            <div class="mil-text-center mil-mb-60">
                <h2 class="mil-light mil-mb-20 mil-up">Personal Checking Options</h2>
                <p class="mil-text-l mil-pale-2 mil-up">Choose the account that keeps pace with the way you live, earn, and spend.</p>
            </div>
            <div class="row">
                <div class="col-xl-4 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                        <img src="{{ asset('img/inner-pages/icons/1.svg') }}" alt="Basic Checking icon" class="mil-mb-30 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">Basic Checking</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            No minimum opening balance and no monthly service charge with direct deposit. Includes free eStatements and digital banking tools for everyday spending.
                        </p>
                    </div>
                </div>
                <div class="col-xl-4 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                        <img src="{{ asset('img/inner-pages/icons/2.svg') }}" alt="Interest Checking icon" class="mil-mb-30 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">Interest Checking</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Earn competitive interest as your balance grows. Enjoy the same no-minimum opening balance, free eStatements, and access to online banking and bill pay.
                        </p>
                    </div>
                </div>
                <div class="col-xl-4 mil-mb-30">
                    <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                        <img src="{{ asset('img/inner-pages/icons/3.svg') }}" alt="55 Plus Checking icon" class="mil-mb-30 mil-up">
                        <h5 class="mil-mb-20 mil-light mil-up">55+ Interest Checking</h5>
                        <p class="mil-text-s mil-soft mil-up">
                            Designed for clients 55 and over with no monthly service charge, no balance requirements, free standard checks, and secure digital tools included.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- account types end -->

    <!-- features -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row flex-sm-row-reverse justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">One Account, Multiple Pockets</h2>
                    <p class="mil-text-m mil-soft mil-mb-15 mil-up">
                        Separate your spending and savings without opening extra products. Move money between your main balance and savings pocket instantly with unlimited transfers.
                    </p>
                    <p class="mil-text-m mil-soft mil-mb-30 mil-up">
                        Schedule automatic savings goals from $5,000 monthly or fortnightly. Withdraw whenever you need, and keep everything organized inside one secure dashboard.
                    </p>
                    <div class="mil-up">
                        <a href="{{ route('personal.customer-support') }}" class="mil-btn mil-m mil-add-arrow">Talk to a Specialist</a>
                    </div>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="{{ asset('img/inner-pages/4.png') }}" alt="Savings pockets illustration" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- features end -->

    <!-- benefits -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-100">
                <div class="row justify-content-center mil-text-center">
                    <div class="col-xl-8 mil-mb-80-adaptive-30">
                        <h2 class="mil-up">Benefits for Account Holders</h2>
                        <p class="mil-text-m mil-soft mil-up">
                            Banko customers enjoy layered security, real-time awareness, and tools that put them in control around the clock.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/4.svg') }}" alt="Interest icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Earn up to 7%</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                Grow balances faster with competitive rates and tools that help you maximize every deposit.
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/5.svg') }}" alt="Alerts icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Smart Alerts</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                Get text and email notifications for large deposits, withdrawals, and card purchases so you can respond instantly.
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="{{ asset('img/inner-pages/icons/6.svg') }}" alt="Security icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Security First</h5>
                            <p class="mil-text-m mil-soft mil-up">
                                Chip-enabled debit cards, fraud monitoring, and international access keep your funds protected at home and abroad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- benefits end -->

    <!-- final cta -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-160" style="background-image: url({{ asset('img/home-3/5.png') }})">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xl-7 mil-sm-text-center">
                        <h2 class="mil-light mil-mb-30 mil-up">Open an Account in Minutes</h2>
                        <p class="mil-text-m mil-mb-60 mil-dark-soft mil-up">
                            Set up digital access, choose the right checking option, and start banking with confidence today.
                        </p>
                        <div class="mil-buttons-frame mil-up">
                            <a href="{{ route('register') }}" class="mil-btn mil-md">Get Started</a>
                            <a href="{{ route('personal.customer-support') }}" class="mil-btn mil-border mil-md">Request a Call</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- final cta end -->

</div>
@endsection

