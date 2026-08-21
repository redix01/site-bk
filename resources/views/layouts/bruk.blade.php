<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('bruk/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/swiper.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/scrollcue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('bruk/css/dark-theme.css') }}">

    <title>@yield('title', config('app.name', 'Banko') . ' – Digital Banking Made Simple')</title>
    <meta name="description" content="@yield('meta_description', config('app.name', 'Banko') . ' is a digital bank built for everyday people — open an account online, move money instantly, and grow your savings from one secure dashboard.')">
    <link rel="icon" type="image/png" href="{{ asset('bruk/img/favicon.webp') }}">
</head>
<body>

    <!-- Preloader Start -->
    <div class="preloader-area" id="preloader">
        <div class="loader">
            <div class="waviy">
                @foreach (str_split(config('app.name', 'Banko')) as $letter)
                    <span>{{ $letter }}</span>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Preloader End -->

    <!-- Theme Switcher Start -->
    <div class="switch-theme-mode">
        <label id="switch" class="switch">
            <input type="checkbox" onchange="toggleTheme()" id="slider">
            <span class="slider round"></span>
        </label>
    </div>
    <!-- Theme Switcher End -->

    <!-- Navbar Area Start -->
    <div class="navbar-area style-three position-absolute top-0 start-0 w-100" id="navbar">
        <div class="container pe-xxl-0">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center">
                <a href="{{ route('home') }}" class="logo d-lg-none">
                    <span class="logo-light fs-24 fw-bold text-title">{{ config('app.name', 'Banko') }}</span>
                    <span class="logo-dark fs-24 fw-bold text-white">{{ config('app.name', 'Banko') }}</span>
                </a>
                <a class="navbar-toggler d-lg-none" data-bs-toggle="offcanvas" href="#navbarOffcanvas" role="button" aria-controls="navbarOffcanvas">
                    <span class="burger-menu">
                        <span class="top-bar"></span>
                        <span class="middle-bar"></span>
                        <span class="bottom-bar"></span>
                    </span>
                </a>
                <div class="collapse navbar-collapse">
                    <a href="{{ route('home') }}" class="logo">
                        <span class="logo-light fs-24 fw-bold text-title">{{ config('app.name', 'Banko') }}</span>
                        <span class="logo-dark fs-24 fw-bold text-white">{{ config('app.name', 'Banko') }}</span>
                    </a>
                    @include('layouts.partials.bruk-nav')
                    <div class="others-option d-flex align-items-center">
                        <a href="{{ route('login') }}" class="link style-four me-3">Log In</a>
                        <a href="{{ route('register') }}" class="btn style-four">Open an Account</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar Area End -->

    <!-- Start Responsive Navbar Area -->
    <div class="responsive-navbar offcanvas offcanvas-end border-0" data-bs-backdrop="static" tabindex="-1" id="navbarOffcanvas">
        <div class="offcanvas-header">
            <a href="{{ route('home') }}" class="logo d-inline-block">
                <span class="logo-light fs-24 fw-bold text-title">{{ config('app.name', 'Banko') }}</span>
                <span class="logo-dark fs-24 fw-bold text-white">{{ config('app.name', 'Banko') }}</span>
            </a>
            <button type="button" class="close-btn bg-transparent position-relative lh-1 p-0 border-0" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <ul class="responsive-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                <li class="responsive-menu-list"><a href="javascript:void(0);">Personal Banking</a>
                    <ul class="responsive-menu-items">
                        <li><a href="{{ route('personal.banking-services') }}">Checking &amp; Savings</a></li>
                        <li><a href="{{ route('transfer') }}">Transfers &amp; Payments</a></li>
                        <li><a href="{{ route('deposit') }}">Deposits</a></li>
                        <li><a href="{{ route('invest') }}">Investing</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('personal.customer-support') }}">Help Center</a></li>
                <li><a href="{{ route('about') }}#contact">Contact Us</a></li>
            </ul>
            <div class="option-item">
                <a href="{{ route('login') }}" class="link style-four d-block mb-3">Log In</a>
                <a href="{{ route('register') }}" class="btn style-four">Open an Account</a>
            </div>
        </div>
    </div>
    <!-- End Responsive Navbar Area -->

    @yield('content')

    <!-- Footer Start -->
    <footer class="footer-wrap position-relative bg-optional pt-130">
        <img src="{{ asset('bruk/img/footer-shape-1.webp') }}" alt="" class="footer-shape-one position-absolute sm-none">
        <img src="{{ asset('bruk/img/footer-shape-2.webp') }}" alt="" class="footer-shape-two position-absolute end-0 sm-none">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-4">
                    <h3 class="fs-24 mb-0">Subscribe To Our Newsletter</h3>
                </div>
                <div class="col-xl-8 ps-xxl-0">
                    <div class="subscribe-form d-flex align-items-center">
                        <p class="mb-0 fw-semibold">Enter your email</p>
                        <form action="#" class="d-flex flex-grow-1 align-items-center">
                            <input type="email" class="bg-transparent h-52">
                            <button class="btn">Subscribe Now</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="footer-widget-wrap d-flex flex-wrap pb-100">
                <div class="footer-widget mb-25">
                    <a href="{{ route('home') }}" class="logo">
                        <span class="logo-light fs-24 fw-bold text-title">{{ config('app.name', 'Banko') }}</span>
                        <span class="logo-dark fs-24 fw-bold text-white">{{ config('app.name', 'Banko') }}</span>
                    </a>
                    <p class="comp-desc">{{ config('app.name', 'Banko') }} is a digital bank built for everyday people — simple accounts, fast transfers, and real support, wherever you are.</p>
                    <ul class="social-profile list-unstyle">
                        <li><a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="ri-facebook-fill"></i></a></li>
                        <li><a href="https://www.instagram.com/" target="_blank" rel="noopener"><i class="ri-instagram-line"></i></a></li>
                        <li><a href="https://www.twitter.com/" target="_blank" rel="noopener"><i class="ri-twitter-x-line"></i></a></li>
                    </ul>
                </div>
                <div class="footer-widget mb-25">
                    <h3 class="footer-widget-title fs-20 fw-medium">Quick Links</h3>
                    <ul class="footer-menu list-unstyle">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('about') }}#contact">Contact Us</a></li>
                        <li><a href="{{ route('personal.customer-support') }}">Help Center</a></li>
                    </ul>
                </div>
                <div class="footer-widget mb-25">
                    <h3 class="footer-widget-title fs-20 fw-medium">Products</h3>
                    <ul class="footer-menu list-unstyle">
                        <li><a href="{{ route('personal.banking-services') }}">Checking</a></li>
                        <li><a href="{{ route('personal.banking-services') }}">Savings</a></li>
                        <li><a href="{{ route('transfer') }}">Transfers</a></li>
                        <li><a href="{{ route('invest') }}">Investing</a></li>
                        <li><a href="{{ route('deposit.crypto') }}">Crypto Deposits</a></li>
                    </ul>
                </div>
                <div class="footer-widget mb-25">
                    <h3 class="footer-widget-title fs-20 fw-medium">Our Contact</h3>
                    <ul class="contact-list list-unstyle">
                        <li class="position-relative"><i class="ri-message-2-line"></i><a href="mailto:{{ config('mail.from.address', 'support@example.com') }}">Support Email</a></li>
                        <li class="position-relative"><i class="ri-calendar-line"></i>Mon – Fri, business hours</li>
                    </ul>
                </div>
            </div>
        </div>
        <p class="copyright-text text-center text-offwhite mb-0"><i class="ri-copyright-line"></i> {{ date('Y') }} <span class="text-white fw-semibold">{{ config('app.name', 'Banko') }}</span>. All rights reserved.</p>
    </footer>
    <!-- Footer End -->

    <!-- Back to Top -->
    <button type="button" id="backtotop" class="position-fixed text-center border-0 p-0">
        <i class="ri-arrow-up-line"></i>
    </button>

    <script src="{{ asset('bruk/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('bruk/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('bruk/js/fslightbox.js') }}"></script>
    <script src="{{ asset('bruk/js/scrollcue.min.js') }}"></script>
    <script src="{{ asset('bruk/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
