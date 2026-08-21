@extends('pages.layout.app')

@section('content')
<div id="smooth-content">

<!-- banner -->
<div class="mil-banner mil-dark-2">
    <div class="mil-radial-g-2"></div>
    <div class="mil-radial-g-3"></div>
    <div class="container">
        <div class="row align-items-center mil-mb-80">
            <div class="col-xl-5">
                <div class="mil-banner-text">
                    <div class="mil-text-l mil-light mil-mb-20">Modern banking, tailored to every stage of life.</div>
                    <h1 class="mil-display mil-light mil-mb-60">Bank with Confidence. Bank with Banko.</h1>
                    <div class="mil-buttons-frame">
                        <a href="{{ route('personal.open-account') }}" class="mil-btn mil-md mil-add-arrow">Open an Account</a>
                        <a href="{{ route('personal.customer-support') }}" class="mil-btn mil-md mil-transp mil-add-play">Talk to a Banker</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="mil-banner-img mil-banner-img-out">
                    <img src="{{ asset('img/home-5/1.png') }}" alt="banner" style="max-width:150%">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner end -->

<!-- about -->
<div class="mil-cta">
    <div class="container">
        <div class="mil-out-frame mil-out-top mil-visible">
            <div class="row flex-sm-row-reverse justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-60">
                    <h2 class="mil-mb-30 mil-light mil-up">Banking That Moves With You</h2>
                    <p class="mil-text-l mil-pale-2 mil-up mil-mb-60">From digital-first tools to expert support in-branch, Banko keeps your finances running smoothly. Pay bills, transfer funds, and manage savings from one secure platform—backed by real people who know your goals.</p>
                    <div class="mil-up"><a href="{{ route('personal.banking-services') }}" class="mil-btn mil-m mil-add-arrow">Explore Services</a></div>
                </div>
                <div class="col-xl-6">
                    <img src="{{ asset('img/home-5/2.png') }}" alt="img" style="width: 100%" class="mil-up">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about end -->

<!-- icon boxes -->
<div class="icon-boxes mil-p-160-130">
    <div class="container">
        <div class="mil-text-center">
            <h2 class="mil-light mil-mb-30 mil-up">Why House Your Money with Banko?</h2>
            <p class="mil-text-l mil-pale-2 mil-mb-60 mil-up">Three pillars that keep your finances protected, empowered, and growing.</p>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-4 mil-mb-30">
                <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                    <img src="{{ asset('img/home-5/icons/1.svg') }}" alt="icon" class="mil-mb-30 mil-up">
                    <h5 class="mil-mb-20 mil-light mil-up">Digital Tools That Deliver</h5>
                    <p class="mil-text-s mil-soft mil-up">Track spending, automate savings, and manage cards in real time with a mobile experience built for clarity.</p>
                </div>
            </div>
            <div class="col-xl-4 mil-mb-30">
                <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                    <img src="{{ asset('img/home-5/icons/2.svg') }}" alt="icon" class="mil-mb-30 mil-up">
                    <h5 class="mil-mb-20 mil-light mil-up">Security You Can Trust</h5>
                    <p class="mil-text-s mil-soft mil-up">Layered authentication and real-time fraud monitoring keep your accounts protected around the clock.</p>
                </div>
            </div>
            <div class="col-xl-4 mil-mb-30">
                <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                    <img src="{{ asset('img/home-5/icons/3.svg') }}" alt="icon" class="mil-mb-30 mil-up">
                    <h5 class="mil-mb-20 mil-light mil-up">Transparent Pricing</h5>
                    <p class="mil-text-s mil-soft mil-up">No surprise fees—just clear, upfront pricing on every account, loan, and investment solution we offer.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- icon boxes end -->

<!-- account steps -->
<div class="mil-dark-2 mil-p-160-130">
    <div class="container">
        <div class="mil-text-center mil-mb-60">
            <h2 class="mil-light mil-mb-20 mil-up">Your Account in Three Simple Steps</h2>
            <p class="mil-text-l mil-pale-2 mil-up">Open your Banko account with a streamlined, secure onboarding experience.</p>
        </div>
        <div class="row">
            <div class="col-xl-4 mil-mb-30">
                <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                    <div class="mil-mb-20">
                        <span class="mil-text-s mil-accent">STEP 01</span>
                    </div>
                    <img src="{{ asset('img/home-5/icons/1.svg') }}" alt="Personal details icon" class="mil-mb-30 mil-up">
                    <h5 class="mil-mb-20 mil-light mil-up">Fill Your Personal Details</h5>
                    <p class="mil-text-s mil-soft mil-up">Share essential information to get started. Our guided form ensures accuracy and keeps your data protected.</p>
                </div>
            </div>
            <div class="col-xl-4 mil-mb-30">
                <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                    <div class="mil-mb-20">
                        <span class="mil-text-s mil-accent">STEP 02</span>
                    </div>
                    <img src="{{ asset('img/home-5/icons/2.svg') }}" alt="KYC verification icon" class="mil-mb-30 mil-up">
                    <h5 class="mil-mb-20 mil-light mil-up">Complete KYC Verification</h5>
                    <p class="mil-text-s mil-soft mil-up">Verify your identity with our secure digital KYC process. Stay compliant while protecting your transactions.</p>
                </div>
            </div>
            <div class="col-xl-4 mil-mb-30">
                <div class="mil-icon-box mil-with-bg mil-dark-2 mil-center mil-up">
                    <div class="mil-mb-20">
                        <span class="mil-text-s mil-accent">STEP 03</span>
                    </div>
                    <img src="{{ asset('img/home-5/icons/3.svg') }}" alt="Savings icon" class="mil-mb-30 mil-up">
                    <h5 class="mil-mb-20 mil-light mil-up">Start Your Savings</h5>
                    <p class="mil-text-s mil-soft mil-up">Activate tailored savings plans with competitive rates, and begin growing your balance right away.</p>
                </div>
            </div>
        </div>
        <div class="mil-text-center mil-up">
            <a href="{{ route('personal.open-account') }}" class="mil-btn mil-m mil-add-arrow">Open Account</a>
        </div>
    </div>
</div>
<!-- account steps end -->

<!-- facts -->
<div class="mil-p-0-80">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-5 mil-mb-80">
                <h2 class="mil-light mil-mb-30 mil-up">Banko by the Numbers</h2>
                <p class="mil-text-l mil-pale-2 mil-mb-60 mil-up">A trusted partner for households, businesses, and communities nationwide.</p>
                <ul class="mil-list-2 mil-type-2 mil-accent mil-mb-60">
                    <li>
                        <div class="mil-up">
                            <h5 class="mil-light mil-mb-15">Serving 40+ Markets</h5>
                            <p class="mil-text-m mil-pale-2">From local branches to virtual consultations, we’re present where our customers live and work.</p>
                        </div>
                    </li>
                    <li>
                        <div class="mil-up">
                            <h5 class="mil-light mil-mb-15">Customer Satisfaction 96%</h5>
                            <p class="mil-text-m mil-pale-2">Our teams resolve most support requests in under 15 minutes, earning industry-leading satisfaction scores.</p>
                        </div>
                    </li>
                </ul>
                <div class="mil-up"><a href="{{ url('/about') }}" class="mil-btn mil-m mil-add-arrow">Learn More</a></div>
            </div>
            <div class="col-xl-6 mil-mb-80">
                <div class="row">
                    <div class="col-xl-6 mil-mb-30">
                        <p class="h1 mil-display mil-mb-15"><span class="mil-accent mil-counter" data-number="2.1">2.1</span><span class="mil-accent">M</span></p>
                        <h5 class="mil-light">Retail Customers</h5>
                    </div>
                    <div class="col-xl-6 mil-mb-30">
                        <p class="h1 mil-display mil-mb-15"><span class="mil-accent">+</span><span class="mil-accent mil-counter" data-number="800">800</span></p>
                        <h5 class="mil-light">Corporate & Commercial Clients</h5>
                    </div>
                    <div class="col-xl-6 mil-mb-30">
                        <p class="h1 mil-display mil-mb-15"><span class="mil-accent">+</span><span class="mil-accent mil-counter" data-number="8.8">8.8</span><span class="mil-accent">K</span></p>
                        <h5 class="mil-light">Dedicated Advisors & Specialists</h5>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- facts end -->

<!-- call to action -->
<div class="mil-cta mil-p-0-160 mil-up">
    <div class="container">
        <div class="mil-out-frame mil-bg-2">
            <div class="row justify-content-center align-items-center mil-p-160-160">
                <div class="col-xl-7 mil-text-center">
                    <h2 class="mil-light mil-mb-30 mil-up">Your Money, Working 24/7</h2>
                    <p class="mil-text-l mil-light mil-mb-60 mil-up">Set savings goals, automate investments, and monitor cash flow with dashboards that update in real time. Wherever you are, Banko keeps you connected to what matters.</p>
                    <div class="mil-up"><a href="{{ route('register') }}" class="mil-btn mil-md mil-add-arrow">Get Started</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- call to action end -->

<!-- features -->
<div class="mil-features mil-p-0-80">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-xl-6 mil-mb-80">
                <h2 class="mil-mb-30 mil-light mil-up">Personal Banking, Elevated</h2>
                <p class="mil-text-l mil-pale-2 mil-mb-60 mil-up">Everyday finances feel effortless with flexible accounts, actionable insights, and support when you need it.</p>
                <ul class="mil-list-1 mil-accent">
                    <li>
                <div class="mil-up">
                    <h5 class="mil-mb-15 mil-light mil-up">Smart Checking &amp; Savings</h5>
                    <p class="mil-text-m mil-pale-2 mil-up">Earn interest, waive fees, and see cash flow at a glance with customizable alerts and budgeting tools.</p>
                </div>
                    </li>
                    <li>
                <div class="mil-up">
                    <h5 class="mil-mb-15 mil-light mil-up">Flexible Credit Options</h5>
                    <p class="mil-text-m mil-pale-2 mil-up">Choose from low-rate credit cards, personal loans, or lines of credit designed for planned and unexpected expenses.</p>
                </div>
                    </li>
                    <li>
                <div class="mil-up">
                    <h5 class="mil-mb-15 mil-light mil-up">Guided Wealth Building</h5>
                    <p class="mil-text-m mil-pale-2 mil-up">Work with our advisors or use self-directed tools to plan for education, retirement, and everything in between.</p>
                </div>
                    </li>
                </ul>
            </div>
            <div class="col-xl-5 mil-mb-80">
                <div class="mil-image-frame mil-up">
                    <img src="{{ asset('img/home-5/3.png') }}" alt="image" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- features end -->

<!-- features -->
<div class="mil-features mil-p-0-80">
    <div class="container">
        <div class="row flex-sm-row-reverse justify-content-between align-items-center">
            <div class="col-xl-6 mil-mb-80">
                <h2 class="mil-mb-30 mil-light mil-up">Powering Ambitious Businesses</h2>
                <p class="mil-text-l mil-pale-2 mil-mb-60 mil-up">Streamline payables, improve liquidity, and finance growth with solutions tailored to your industry and scale.</p>
                    <div class="mil-up">
                        <p class="mil-text-m mil-pale-2 mil-up">Connect with our commercial banking team for tailored treasury and lending advice that scales with your operations.</p>
                    </div>
            </div>
            <div class="col-xl-5 mil-mb-80">
                <div class="mil-image-frame mil-up">
                    <img src="{{ asset('img/home-5/4.png') }}" alt="image" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- features end -->

<!-- testimonials -->
<div class="mil-cta mil-up">
    <div class="container">
        <div class="mil-out-frame mil-bg-4 mil-p-160-160">
            <div class="row justify-content-center">
                <div class="col-xl-7 mil-relative">
                    <div class="swiper-container mil-testimonials-1 mil-up">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <blockquote class="mil-center" data-swiper-parallax="-400" data-swiper-parallax-opacity="0" data-swiper-parallax-scale="0.8">
                                    <svg width="50" height="32" viewBox="0 0 50 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="mil-mb-30 mil-up mil-accent">
                                        <path d="M13.0425 9.59881C13.734 7.27646 15.0099 5.16456 16.7515 3.45982C17.0962 3.11455 17.2958 2.65336 17.31 2.16891C17.3243 1.68445 17.1523 1.2126 16.8285 0.848135L16.6225 0.619235C16.3552 0.313531 15.9908 0.106228 15.5887 0.0311485C15.1866 -0.0439312 14.7706 0.0176452 14.4085 0.205827C-0.299477 8.01918 -0.116489 18.6169 0.0295105 20.4165C0.0195105 20.6139 -0.000488281 20.8112 -0.000488281 21.0085C0.0518962 23.1543 0.724816 25.2405 1.93898 27.0214C3.15314 28.8023 4.85796 30.2037 6.85252 31.0604C8.84709 31.9171 11.0483 32.1935 13.1967 31.8569C15.3452 31.5203 17.3514 30.5848 18.9788 29.1606C20.6063 27.7364 21.7873 25.8829 22.3826 23.8185C22.9779 21.7541 22.9627 19.5648 22.3389 17.5086C21.715 15.4524 20.5085 13.615 18.8614 12.2129C17.2144 10.8108 15.1954 9.90246 13.0425 9.59487V9.59881Z" fill="#F27457" />
                                        <path d="M40.2255 9.59881C40.9171 7.27648 42.193 5.16459 43.9345 3.45982C44.2793 3.11455 44.4788 2.65336 44.4931 2.16891C44.5074 1.68445 44.3353 1.2126 44.0115 0.848135L43.8055 0.619235C43.5382 0.313531 43.1738 0.106228 42.7717 0.0311485C42.3696 -0.0439312 41.9536 0.0176452 41.5915 0.205827C26.8835 8.01918 27.0665 18.6169 27.2115 20.4165C27.2015 20.6139 27.1815 20.8112 27.1815 21.0085C27.2332 23.1544 27.9055 25.241 29.1191 27.0224C30.3328 28.8038 32.0373 30.2057 34.0318 31.063C36.0262 31.9203 38.2274 32.1972 40.3761 31.8611C42.5248 31.525 44.5313 30.5899 46.1591 29.166C47.787 27.742 48.9684 25.8887 49.5641 23.8242C50.1599 21.7598 50.1451 19.5704 49.5215 17.514C48.8979 15.4576 47.6915 13.6199 46.0445 12.2176C44.3975 10.8152 42.3785 9.90659 40.2255 9.59881Z" fill="#F27457" />
                                    </svg>
                                    <p class="mil-text-l mil-mb-60 mil-up mil-light">“Switching to Banko gave our family a single view of every account. Automatic savings boosts and real-time alerts mean we never miss a payment.”</p>
                                    <img src="{{ asset('img/faces/2.jpg') }}" alt="Customer" class="mil-mb-15 mil-up">
                                    <h5 class="mil-up mil-light">Beth Nilsson</h5>
                                </blockquote>
                            </div>
                            <div class="swiper-slide">
                                <blockquote class="mil-center" data-swiper-parallax="-400" data-swiper-parallax-opacity="0" data-swiper-parallax-scale="0.8">
                                    <svg width="50" height="32" viewBox="0 0 50 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="mil-mb-30 mil-up mil-accent">
                                        <path d="M13.0425 9.59881C13.734 7.27646 15.0099 5.16456 16.7515 3.45982C17.0962 3.11455 17.2958 2.65336 17.31 2.16891C17.3243 1.68445 17.1523 1.2126 16.8285 0.848135L16.6225 0.619235C16.3552 0.313531 15.9908 0.106228 15.5887 0.0311485C15.1866 -0.0439312 14.7706 0.0176452 14.4085 0.205827C-0.299477 8.01918 -0.116489 18.6169 0.0295105 20.4165C0.0195105 20.6139 -0.000488281 20.8112 -0.000488281 21.0085C0.0518962 23.1543 0.724816 25.2405 1.93898 27.0214C3.15314 28.8023 4.85796 30.2037 6.85252 31.0604C8.84709 31.9171 11.0483 32.1935 13.1967 31.8569C15.3452 31.5203 17.3514 30.5848 18.9788 29.1606C20.6063 27.7364 21.7873 25.8829 22.3826 23.8185C22.9779 21.7541 22.9627 19.5648 22.3389 17.5086C21.715 15.4524 20.5085 13.615 18.8614 12.2129C17.2144 10.8108 15.1954 9.90246 13.0425 9.59487V9.59881Z" fill="#03A6A6" />
                                        <path d="M40.2255 9.59881C40.9171 7.27648 42.193 5.16459 43.9345 3.45982C44.2793 3.11455 44.4788 2.65336 44.4931 2.16891C44.5074 1.68445 44.3353 1.2126 44.0115 0.848135L43.8055 0.619235C43.5382 0.313531 43.1738 0.106228 42.7717 0.0311485C42.3696 -0.0439312 41.9536 0.0176452 41.5915 0.205827C26.8835 8.01918 27.0665 18.6169 27.2115 20.4165C27.2015 20.6139 27.1815 20.8112 27.1815 21.0085C27.2332 23.1544 27.9055 25.241 29.1191 27.0224C30.3328 28.8038 32.0373 30.2057 34.0318 31.063C36.0262 31.9203 38.2274 32.1972 40.3761 31.8611C42.5248 31.525 44.5313 30.5899 46.1591 29.166C47.787 27.742 48.9684 25.8887 49.5641 23.8242C50.1599 21.7598 50.1451 19.5704 49.5215 17.514C48.8979 15.4576 47.6915 13.6199 46.0445 12.2176C44.3975 10.8152 42.3785 9.90659 40.2255 9.59881Z" fill="#03A6A6" />
                                    </svg>
                                    <p class="mil-text-l mil-mb-60 mil-up mil-light">“Our treasury team relies on Banko for cash flow forecasting and same-day wires. The platform makes complex transactions feel effortless.”</p>
                                    <img src="{{ asset('img/faces/1.jpg') }}" alt="Customer" class="mil-mb-15 mil-up">
                                    <h5 class="mil-up mil-light">Karl Andreassen, CFO</h5>
                                </blockquote>
                            </div>
                            <div class="swiper-slide">
                                <blockquote class="mil-center" data-swiper-parallax="-400" data-swiper-parallax-opacity="0" data-swiper-parallax-scale="0.8">
                                    <svg width="50" height="32" viewBox="0 0 50 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="mil-mb-30 mil-up mil-accent">
                                        <path d="M13.0425 9.59881C13.734 7.27646 15.0099 5.16456 16.7515 3.45982C17.0962 3.11455 17.2958 2.65336 17.31 2.16891C17.3243 1.68445 17.1523 1.2126 16.8285 0.848135L16.6225 0.619235C16.3552 0.313531 15.9908 0.106228 15.5887 0.0311485C15.1866 -0.0439312 14.7706 0.0176452 14.4085 0.205827C-0.299477 8.01918 -0.116489 18.6169 0.0295105 20.4165C0.0195105 20.6139 -0.000488281 20.8112 -0.000488281 21.0085C0.0518962 23.1543 0.724816 25.2405 1.93898 27.0214C3.15314 28.8023 4.85796 30.2037 6.85252 31.0604C8.84709 31.9171 11.0483 32.1935 13.1967 31.8569C15.3452 31.5203 17.3514 30.5848 18.9788 29.1606C20.6063 27.7364 21.7873 25.8829 22.3826 23.8185C22.9779 21.7541 22.9627 19.5648 22.3389 17.5086C21.715 15.4524 20.5085 13.615 18.8614 12.2129C17.2144 10.8108 15.1954 9.90246 13.0425 9.59487V9.59881Z" fill="#03A6A6" />
                                        <path d="M40.2255 9.59881C40.9171 7.27648 42.193 5.16459 43.9345 3.45982C44.2793 3.11455 44.4788 2.65336 44.4931 2.16891C44.5074 1.68445 44.3353 1.2126 44.0115 0.848135L43.8055 0.619235C43.5382 0.313531 43.1738 0.106228 42.7717 0.0311485C42.3696 -0.0439312 41.9536 0.0176452 41.5915 0.205827C26.8835 8.01918 27.0665 18.6169 27.2115 20.4165C27.2015 20.6139 27.1815 20.8112 27.1815 21.0085C27.2332 23.1544 27.9055 25.241 29.1191 27.0224C30.3328 28.8038 32.0373 30.2057 34.0318 31.063C36.0262 31.9203 38.2274 32.1972 40.3761 31.8611C42.5248 31.525 44.5313 30.5899 46.1591 29.166C47.787 27.742 48.9684 25.8887 49.5641 23.8242C50.1599 21.7598 50.1451 19.5704 49.5215 17.514C48.8979 15.4576 47.6915 13.6199 46.0445 12.2176C44.3975 10.8152 42.3785 9.90659 40.2255 9.59881Z" fill="#03A6A6" />
                                    </svg>
                                    <p class="mil-text-l mil-mb-60 mil-up mil-light">“The wealth team mapped out our retirement and estate plan in one session. Their guidance keeps our investments on track.”</p>
                                    <img src="{{ asset('img/faces/3.jpg') }}" alt="Customer" class="mil-mb-15 mil-up">
                                    <h5 class="mil-up mil-light">Rüdiger Karlsen</h5>
                                </blockquote>
                            </div>
                        </div>
                        <div class="mil-slider-nav-1">
                            <div class="mil-testi-prev"></div>
                            <div class="mil-testi-next"></div>
                        </div>
                    </div>
                    <div class="mil-testi-pagination mil-up"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- testimonials end -->

<!-- brands -->
<div class="mil-p-160-130">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-6 mil-mb-60">
                <h2 class="mil-light mil-mb-30 mil-up">Solutions for Every Banking Journey</h2>
                <p class="mil-text-l mil-pale-2 mil-up mil-mb-60">Combine tailored guidance with technology that keeps you connected to capital, insights, and opportunities.</p>
                <div class="mil-up"><a href="{{ route('personal.banking-services') }}" class="mil-btn mil-m mil-add-arrow">View All Solutions</a></div>
            </div>
            <div class="col-xl-6">
                <div class="mil-brand-card mil-mb-30 mil-up">
                    <img src="{{ asset('img/brands/1.svg') }}" alt="brand" class="mil-mb-30">
                    <h5 class="mil-light mil-light mil-mb-20">Digital Banking</h5>
                    <p class="mil-text-s mil-pale-2">All-in-one mobile and online platform for transfers, budgeting, card controls, and personalized insights.</p>
                </div>
                <div class="mil-brand-card mil-mb-30 mil-up">
                    <img src="{{ asset('img/brands/2.svg') }}" alt="brand" class="mil-mb-30">
                    <h5 class="mil-light mil-light mil-mb-20">Commercial Banking</h5>
                    <p class="mil-text-s mil-pale-2">Treasury management, payment automation, and tailored lending that scale with your business.</p>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="mil-brand-card mil-mb-30 mil-up">
                    <img src="{{ asset('img/brands/3.svg') }}" alt="brand" class="mil-mb-30">
                    <h5 class="mil-light mil-light mil-mb-20">Wealth Management</h5>
                    <p class="mil-text-s mil-pale-2">Dedicated advisors craft investment, retirement, and trust strategies aligned to your ambitions.</p>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="mil-brand-card mil-mb-30 mil-up">
                    <img src="{{ asset('img/brands/4.svg') }}" alt="brand" class="mil-mb-30">
                    <h5 class="mil-light mil-light mil-mb-20">Community Impact</h5>
                    <p class="mil-text-s mil-pale-2">Local lending and financial education programs that expand access to capital for underserved communities.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- brands end -->

<!-- footer -->
<footer class="mil-footer-dark-2 mil-p-160-0">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-md-6 mil-mb-60">
                <h2 class="mil-light mil-mb-30">{{ env('APP_NAME') }}</h2>
                <p class="mil-text-s mil-pale">
                    Modern banking solutions designed for individuals and businesses seeking secure, digital-first experiences.
                </p>
            </div>
            <div class="col-xl-4 col-md-6 mil-mb-60">
                <h6 class="mil-mb-60 mil-soft">Useful Links</h6>
                <ul class="mil-footer-list">
                    <li class="mil-text-m mil-pale mil-mb-15">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="mil-text-m mil-pale mil-mb-15">
                        <a href="{{ url('/about') }}">About Us</a>
                    </li>
                    <li class="mil-text-m mil-pale mil-mb-15">
                        <a href="{{ route('personal.banking-services') }}">Services</a>
                    </li>
                    <li class="mil-text-m mil-pale mil-mb-15">
                        <a href="{{ route('personal.customer-support') }}">Support</a>
                    </li>
                    <li class="mil-text-m mil-pale mil-mb-15">
                        <a href="{{ route('login') }}">Client Login</a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-4 col-md-6 mil-mb-60">
                <h6 class="mil-mb-60 mil-soft">Get in Touch</h6>
                <ul class="mil-footer-list">
                    <li class="mil-text-m mil-pale mil-mb-15">
                        999 Rue du Cherche-Midi, 7755500666 Paris, <br>France
                    </li>
                    <li class="mil-text-m mil-pale mil-mb-15">
                        support@shirecommerce.com
                    </li>
                </ul>
            </div>
        </div>
        <div class="mil-footer-bottom">
            <div class="row">
                <div class="col-xl-6">
                    <p class="mil-text-s mil-pale">© 2024 Banko Financial Group</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->

</div>

@endsection