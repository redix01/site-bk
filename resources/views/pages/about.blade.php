@extends('pages.layout.app_layout')

@section('content')
<div id="smooth-content">

    <!-- banner -->
    <div class="mil-banner mil-banner-inner mil-dissolve">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-8">
                    <div class="mil-banner-text mil-text-center">
                        <div class="mil-text-m mil-mb-20">About Banko</div>
                        <h1 class="mil-mb-60">Trusted Banking, Built on Relationships</h1>
                        <ul class="mil-breadcrumbs mil-center">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('/about') }}">About</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- vision -->
    <div class="mil-features mil-p-0-80">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-xl-5 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">The Vision That Drives Our Team</h2>
                    <p class="mil-text-m mil-soft mil-mb-60 mil-up">From our first community branch to today’s national footprint, Banko’s purpose has remained the same: help people and businesses prosper with clarity and confidence. We invest in innovation and in people, so every client interaction feels personal and every solution scales with their goals.</p>
                    <ul class="mil-list-2 mil-type-2">
                        <li>
                            <div class="mil-up">
                                <h5 class="mil-mb-15">Client-First Mindset</h5>
                                <p class="mil-text-m mil-soft">Banking is personal. We take time to understand each relationship so our advice and products create lasting value.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-6 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="img/inner-pages/1.png" alt="Bank team collaborating" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- vision end -->

    <!-- stats -->
    <div class="mil-facts mil-p-0-130">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 mil-sm-text-center mil-mb-30 mil-up">
                    <p class="h1 mil-display mil-mb-15"><span class="mil-accent mil-counter" data-number="2.1">2.1</span><span class="mil-pale">M</span></p>
                    <h5 class="mil-light">Clients Served</h5>
                </div>
                <div class="col-xl-4 mil-sm-text-center mil-mb-30 mil-up">
                    <p class="h1 mil-display mil-mb-15"><span class="mil-accent mil-counter" data-number="800">800</span><span class="mil-pale">+</span></p>
                    <h5 class="mil-light">Corporate Partners</h5>
                </div>
                <div class="col-xl-4 mil-sm-text-center mil-mb-30 mil-up">
                    <p class="h1 mil-display mil-mb-15"><span class="mil-accent mil-counter" data-number="40">40</span><span class="mil-pale">+</span></p>
                    <h5 class="mil-light">Markets Supported</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- stats end -->

    <!-- strengths -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-100">
                <div class="row justify-content-center mil-text-center">
                    <div class="col-xl-8 mil-mb-80-adaptive-30">
                        <h2 class="mil-up">Why Clients Trust Banko</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="img/inner-pages/icons/1.svg" alt="Global network icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Connected Network</h5>
                            <p class="mil-text-m mil-soft mil-up">Our reach spans major financial centers, providing seamless local expertise backed by national resources.</p>
                        </div>
                    </div>
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="img/inner-pages/icons/2.svg" alt="Security icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Uncompromising Security</h5>
                            <p class="mil-text-m mil-soft mil-up">Advanced fraud monitoring, layered authentication, and 24/7 security teams safeguard every interaction.</p>
                        </div>
                    </div>
                    <div class="col-xl-4 mil-mb-60">
                        <div class="mil-icon-box">
                            <img src="img/inner-pages/icons/3.svg" alt="Innovation icon" class="mil-mb-30 mil-up">
                            <h5 class="mil-mb-20 mil-up">Purposeful Innovation</h5>
                            <p class="mil-text-m mil-soft mil-up">We continually invest in tools that make banking simpler—without losing the human insight clients rely on.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- strengths end -->

    <!-- values -->
    <div class="mil-features mil-p-160-80">
        <div class="container">
            <div class="row flex-sm-row-reverse justify-content-between align-items-center">
                <div class="col-xl-6 mil-mb-80">
                    <h2 class="mil-mb-30 mil-up">Values That Anchor Banko</h2>
                    <p class="mil-text-m mil-soft mil-mb-60 mil-up">We blend transparent communication with disciplined risk management to build enduring relationships. Every product, policy, and partnership reflects our promise to steward client wealth responsibly.</p>
                    <ul class="mil-list-2 mil-type-2">
                        <li>
                            <div class="mil-up">
                                <h5 class="mil-mb-15">Transparency in Action</h5>
                                <p class="mil-text-m mil-soft">Clear pricing, proactive updates, and open dialogue ensure clients always know where they stand.</p>
                            </div>
                        </li>
                        <li>
                            <div class="mil-up">
                                <h5 class="mil-mb-15">Security with Accountability</h5>
                                <p class="mil-text-m mil-soft">We pair cutting-edge controls with dedicated teams who monitor, respond, and continuously improve.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-5 mil-mb-80">
                    <div class="mil-image-frame mil-up">
                        <img src="img/inner-pages/2.png" alt="Values illustration" class="mil-scale-img" data-value-1="1" data-value-2="1.2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- values end -->

    <!-- principles -->
    <div class="mil-quote mil-p-160-130">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <h2 class="mil-mb-30">“Trust is our most valuable currency. We earn it through clarity, accountability, and an unwavering focus on our clients.”</h2>
                    <p class="mil-text-m mil-soft mil-mb-60">— The Banko Team</p>
                    <div class="row">
                        <div class="col-xl-6">
                            <ul class="mil-list-2 mil-type-2 mil-mb-30">
                                <li>
                                    <div class="mil-up">
                                        <h5 class="mil-mb-15">Privacy Stewardship</h5>
                                        <p class="mil-text-m mil-soft">We never share customer data without explicit consent and always communicate how information is used.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xl-6">
                            <ul class="mil-list-2 mil-type-2 mil-mb-30">
                                <li>
                                    <div class="mil-up">
                                        <h5 class="mil-mb-15">Data Protection</h5>
                                        <p class="mil-text-m mil-soft">Our security architecture is audited regularly to meet and exceed regulatory standards worldwide.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- principles end -->

    <!-- cta -->
    <div class="mil-cta mil-up">
        <div class="container">
            <div class="mil-out-frame mil-p-160-160" style="background-image: url(img/home-3/5.png)">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xl-7 mil-sm-text-center">
                        <h2 class="mil-light mil-mb-30 mil-up">Experience Banking That Adapts to You</h2>
                        <p class="mil-text-m mil-mb-60 mil-dark-soft mil-up">Partner with Banko and unlock tailored guidance, smarter tools, and a team committed to your long-term success.</p>
                        <div class="mil-buttons-frame mil-up">
                            <a href="contact.html" class="mil-btn mil-md">Connect with Us</a>
                            <a href="register.html" class="mil-btn mil-border mil-md">Open an Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cta end -->

</div>
@endsection

