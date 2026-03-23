<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>@yield('title', env('APP_NAME'))</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="{{ env('APP_NAME') }}">
    <meta name="description" content="@yield('meta_description', 'Digital-first banking for personal and business growth.')">

    <link rel="stylesheet" href="{{ asset('fonts/css/switzer.css') }}" type="text/css" media="all">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <style>
        :root {
            --sc-bg: #06070b;
            --sc-surface: rgba(15, 19, 30, 0.86);
            --sc-surface-strong: #111623;
            --sc-card: rgba(255, 255, 255, 0.04);
            --sc-border: rgba(255, 255, 255, 0.1);
            --sc-text: #f5f7fb;
            --sc-muted: #99a5bb;
            --sc-accent: #66a3ff;
            --sc-accent-soft: rgba(102, 163, 255, 0.18);
            --sc-shadow: 0 24px 80px rgba(0, 0, 0, 0.38);
            --sc-radius: 32px;
            --sc-radius-sm: 18px;
            --sc-container: min(1180px, calc(100% - 40px));
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Switzer", "Helvetica Neue", Arial, sans-serif;
            color: var(--sc-text);
            background:
                radial-gradient(circle at top, rgba(102, 163, 255, 0.16), transparent 38%),
                radial-gradient(circle at bottom left, rgba(14, 78, 161, 0.28), transparent 34%),
                linear-gradient(180deg, #05070d 0%, #080b12 42%, #06070b 100%);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        textarea,
        select {
            font: inherit;
        }

        .sc-page {
            position: relative;
            overflow: hidden;
        }

        .sc-page::before,
        .sc-page::after {
            content: "";
            position: fixed;
            inset: auto;
            pointer-events: none;
            z-index: 0;
            filter: blur(20px);
        }

        .sc-page::before {
            top: 140px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 999px;
            background: rgba(102, 163, 255, 0.18);
        }

        .sc-page::after {
            bottom: 120px;
            left: -60px;
            width: 280px;
            height: 280px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
        }

        .sc-container {
            width: var(--sc-container);
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .sc-section {
            padding: 0 0 112px;
        }

        .sc-section:last-child {
            padding-bottom: 72px;
        }

        .sc-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--sc-accent);
            text-transform: uppercase;
            letter-spacing: 0.24em;
            font-size: 11px;
            font-weight: 600;
        }

        .sc-kicker::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 14px currentColor;
        }

        .sc-eyebrow {
            margin: 0 0 16px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 11px;
            color: var(--sc-accent);
            font-weight: 600;
        }

        .sc-title {
            margin: 0 0 20px;
            font-size: clamp(34px, 5vw, 62px);
            line-height: 0.95;
            letter-spacing: -0.05em;
            font-weight: 300;
        }

        .sc-subtitle {
            margin: 0;
            color: var(--sc-muted);
            font-size: 18px;
            line-height: 1.75;
            max-width: 640px;
        }

        .mil-top-panel {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(18px);
            background: rgba(6, 7, 11, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .mil-top-panel .sc-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 18px 0;
        }

        .mil-logo {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .mil-logo-mark {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ffffff 0%, #d5deec 100%);
            color: #06070b;
            font-size: 18px;
            font-weight: 700;
            box-shadow: 0 16px 30px rgba(255, 255, 255, 0.14);
        }

        .mil-logo-copy {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mil-logo-title {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .mil-logo-note {
            color: var(--sc-muted);
            font-size: 11px;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .mil-top-menu ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .mil-top-menu a {
            color: var(--sc-muted);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            transition: color 0.2s ease, opacity 0.2s ease;
        }

        .mil-top-menu li.mil-active a,
        .mil-top-menu a:hover,
        .mil-top-menu a:focus-visible {
            color: var(--sc-text);
        }

        .mil-menu-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mil-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border-radius: 999px;
            border: 1px solid transparent;
            padding: 14px 22px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .mil-btn:hover,
        .mil-btn:focus-visible {
            transform: translateY(-1px);
        }

        .sc-btn-ghost {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.08);
            color: var(--sc-text);
        }

        .sc-btn-primary {
            background: linear-gradient(135deg, #ffffff 0%, #dbe4f2 100%);
            color: #06070b;
            box-shadow: 0 18px 40px rgba(255, 255, 255, 0.1);
        }

        .mil-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: transparent;
            color: var(--sc-text);
            cursor: pointer;
            padding: 0;
        }

        .mil-menu-btn span {
            position: absolute;
            width: 18px;
            height: 2px;
            border-radius: 999px;
            background: currentColor;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .mil-menu-btn span:first-child {
            transform: translateY(-4px);
        }

        .mil-menu-btn span:last-child {
            transform: translateY(4px);
        }

        .mil-menu-btn.mil-active span:first-child {
            transform: rotate(45deg);
        }

        .mil-menu-btn.mil-active span:last-child {
            transform: rotate(-45deg);
        }

        .sc-main {
            position: relative;
            z-index: 1;
        }

        .progress-wrap {
            position: fixed;
            right: 22px;
            bottom: 22px;
            width: 48px;
            height: 48px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            background: rgba(8, 12, 20, 0.9);
            color: var(--sc-text);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            pointer-events: none;
            transform: translateY(12px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 40;
        }

        .progress-wrap::before {
            content: "Top";
            font-size: 11px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .progress-wrap.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .sc-mobile-only {
            display: none;
        }

        @media (max-width: 1023px) {
            .mil-top-panel .sc-container {
                padding: 16px 0;
            }

            .mil-top-menu {
                position: absolute;
                top: calc(100% + 12px);
                left: 20px;
                right: 20px;
                display: none;
                background: rgba(8, 12, 20, 0.98);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 24px;
                box-shadow: var(--sc-shadow);
            }

            .mil-top-menu.mil-active {
                display: block;
            }

            .mil-top-menu ul {
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 16px;
            }

            .mil-top-menu li + li {
                border-top: 1px solid rgba(255, 255, 255, 0.06);
            }

            .mil-top-menu a {
                display: block;
                padding: 16px 6px;
            }

            .mil-menu-buttons .mil-btn {
                display: none;
            }

            .mil-menu-btn {
                position: relative;
                display: inline-flex;
            }

            .sc-mobile-only {
                display: block;
            }
        }

        @media (max-width: 720px) {
            .sc-container {
                width: min(100% - 28px, 1180px);
            }

            .mil-logo-note {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sc-page">
        <header class="mil-top-panel">
            <div class="sc-container">
                <a href="{{ route('home') }}" class="mil-logo">
                    <span class="mil-logo-mark">B</span>
                    <span class="mil-logo-copy">
                        <span class="mil-logo-title">{{ env('APP_NAME') }}</span>
                        <span class="mil-logo-note">Digital private banking</span>
                    </span>
                </a>

                <nav class="mil-top-menu" id="site-menu">
                    <ul>
                        <li class="{{ request()->routeIs('home') ? 'mil-active' : '' }}">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="{{ request()->routeIs('personal.banking-services') ? 'mil-active' : '' }}">
                            <a href="{{ route('personal.banking-services') }}">Personal</a>
                        </li>
                        <li class="{{ request()->routeIs('personal.open-account') ? 'mil-active' : '' }}">
                            <a href="{{ route('personal.open-account') }}">Open Account</a>
                        </li>
                        <li class="{{ request()->routeIs('personal.customer-support') ? 'mil-active' : '' }}">
                            <a href="{{ route('personal.customer-support') }}">Support</a>
                        </li>
                        <li class="{{ request()->routeIs('about') ? 'mil-active' : '' }}">
                            <a href="{{ route('about') }}">About</a>
                        </li>
                        <li class="sc-mobile-only">
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="sc-mobile-only">
                            <a href="{{ route('register') }}">Join Now</a>
                        </li>
                    </ul>
                </nav>

                <div class="mil-menu-buttons">
                    <a href="{{ route('login') }}" class="mil-btn sc-btn-ghost">Login</a>
                    <a href="{{ route('register') }}" class="mil-btn sc-btn-primary">Join Now</a>
                    <button type="button" class="mil-menu-btn" id="menu-toggle" aria-controls="site-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </header>

        <main class="sc-main">
            @yield('content')
        </main>

        <button type="button" class="progress-wrap" id="back-to-top" aria-label="Back to top"></button>
    </div>

    <script>
        (() => {
            const menuToggle = document.getElementById('menu-toggle');
            const siteMenu = document.getElementById('site-menu');
            const backToTop = document.getElementById('back-to-top');

            if (menuToggle && siteMenu) {
                menuToggle.addEventListener('click', () => {
                    const isOpen = menuToggle.classList.toggle('mil-active');
                    siteMenu.classList.toggle('mil-active', isOpen);
                    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                siteMenu.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        menuToggle.classList.remove('mil-active');
                        siteMenu.classList.remove('mil-active');
                        menuToggle.setAttribute('aria-expanded', 'false');
                    });
                });
            }

            if (backToTop) {
                const toggleBackToTop = () => {
                    backToTop.classList.toggle('is-visible', window.scrollY > 420);
                };

                backToTop.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                toggleBackToTop();
                window.addEventListener('scroll', toggleBackToTop, { passive: true });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
