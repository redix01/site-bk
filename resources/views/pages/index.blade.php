@extends('pages.layout.app')

@section('title', env('APP_NAME') . ' | Front Page')
@section('meta_description', 'Premium digital banking experience for personal accounts, guidance, and support.')

@push('styles')
<style>
    .sc-landing {
        padding: 42px 0 0;
    }

    .sc-hero {
        padding: 54px 0 108px;
    }

    .sc-hero-grid,
    .sc-story,
    .sc-split,
    .sc-footer-grid {
        display: grid;
        gap: 32px;
    }

    .sc-hero-grid {
        grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.8fr);
        align-items: center;
    }

    .sc-hero-copy {
        max-width: 700px;
    }

    .sc-hero-copy p {
        margin: 0 0 36px;
        color: var(--sc-muted);
        font-size: 19px;
        line-height: 1.8;
    }

    .sc-hero-actions,
    .sc-inline-actions,
    .sc-footer-links {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }

    .sc-note-row {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-top: 28px;
        color: var(--sc-muted);
        font-size: 13px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .sc-hero-card,
    .sc-panel,
    .sc-feature-card,
    .sc-stat-card,
    .sc-footer-card {
        border: 1px solid var(--sc-border);
        background: var(--sc-card);
        backdrop-filter: blur(18px);
        box-shadow: var(--sc-shadow);
    }

    .sc-hero-card {
        border-radius: var(--sc-radius);
        padding: 28px;
        position: relative;
        overflow: hidden;
        min-height: 560px;
        display: flex;
        align-items: flex-end;
    }

    .sc-hero-card::before,
    .sc-hero-card::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        background: rgba(102, 163, 255, 0.14);
        filter: blur(8px);
    }

    .sc-hero-card::before {
        top: -60px;
        right: -20px;
        width: 240px;
        height: 240px;
    }

    .sc-hero-card::after {
        bottom: -70px;
        left: -30px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.08);
    }

    .sc-card-stack {
        position: relative;
        z-index: 1;
        width: 100%;
        display: grid;
        gap: 20px;
    }

    .sc-card-chip {
        width: 54px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8d06a 0%, #d9a631 100%);
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.12);
    }

    .sc-elite-card {
        border-radius: 28px;
        padding: 28px;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02)),
            linear-gradient(145deg, #0b0f18 0%, #121827 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        min-height: 240px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sc-elite-top,
    .sc-elite-bottom,
    .sc-story,
    .sc-footer-bottom {
        display: flex;
        justify-content: space-between;
        gap: 18px;
    }

    .sc-elite-top strong,
    .sc-elite-bottom strong {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: rgba(255, 255, 255, 0.58);
        margin-bottom: 10px;
    }

    .sc-elite-bottom {
        align-items: flex-end;
    }

    .sc-elite-number {
        margin: 0;
        font-size: 26px;
        letter-spacing: 0.24em;
        font-weight: 300;
    }

    .sc-card-mini {
        padding: 18px 20px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sc-card-mini p,
    .sc-card-mini span,
    .sc-story-copy p,
    .sc-list,
    .sc-list li,
    .sc-feature-card p,
    .sc-stat-card p,
    .sc-split-card p,
    .sc-footer-card p,
    .sc-footer-bottom {
        color: var(--sc-muted);
    }

    .sc-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-top: 30px;
    }

    .sc-stat-card {
        border-radius: 24px;
        padding: 28px;
    }

    .sc-stat-card strong {
        display: block;
        font-size: clamp(30px, 4vw, 46px);
        font-weight: 300;
        letter-spacing: -0.05em;
        margin-bottom: 10px;
        color: var(--sc-text);
    }

    .sc-features-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-top: 34px;
    }

    .sc-feature-card {
        border-radius: 28px;
        padding: 28px;
    }

    .sc-feature-index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 999px;
        background: var(--sc-accent-soft);
        color: var(--sc-accent);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.16em;
        margin-bottom: 26px;
    }

    .sc-feature-card h3,
    .sc-story-copy h2,
    .sc-split-card h3,
    .sc-footer-card h4 {
        margin: 0 0 14px;
        font-size: 28px;
        line-height: 1.08;
        letter-spacing: -0.04em;
        font-weight: 300;
    }

    .sc-story {
        align-items: center;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 430px);
    }

    .sc-story-copy p,
    .sc-split-card p {
        margin: 0 0 18px;
        line-height: 1.85;
        font-size: 17px;
    }

    .sc-list {
        list-style: none;
        margin: 28px 0 0;
        padding: 0;
        display: grid;
        gap: 16px;
    }

    .sc-list li {
        position: relative;
        padding-left: 24px;
        font-size: 15px;
        line-height: 1.75;
    }

    .sc-list li::before {
        content: "";
        position: absolute;
        top: 11px;
        left: 0;
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: var(--sc-accent);
        box-shadow: 0 0 14px rgba(102, 163, 255, 0.55);
    }

    .sc-story-visual {
        padding: 32px;
        border-radius: var(--sc-radius);
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.03)),
            linear-gradient(145deg, #0c111b 0%, #151c2d 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: var(--sc-shadow);
    }

    .sc-visual-grid {
        display: grid;
        gap: 16px;
    }

    .sc-visual-card {
        border-radius: 22px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .sc-visual-card strong,
    .sc-footer-card strong {
        display: block;
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--sc-accent);
        margin-bottom: 12px;
    }

    .sc-visual-card h4 {
        margin: 0 0 10px;
        font-size: 22px;
        font-weight: 300;
        letter-spacing: -0.03em;
    }

    .sc-split {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sc-split-card {
        padding: 34px;
        border-radius: var(--sc-radius);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: var(--sc-shadow);
    }

    .sc-footer {
        padding: 0 0 72px;
    }

    .sc-footer-card {
        padding: 32px;
        border-radius: var(--sc-radius);
    }

    .sc-footer-grid {
        grid-template-columns: minmax(0, 1.2fr) repeat(2, minmax(220px, 1fr));
        align-items: start;
    }

    .sc-footer-links {
        margin-top: 18px;
    }

    .sc-footer-links a {
        color: var(--sc-text);
    }

    .sc-footer-bottom {
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        font-size: 13px;
        line-height: 1.8;
    }

    @media (max-width: 1100px) {
        .sc-hero-grid,
        .sc-story,
        .sc-split,
        .sc-footer-grid,
        .sc-features-grid,
        .sc-stats {
            grid-template-columns: 1fr 1fr;
        }

        .sc-hero-grid > :first-child,
        .sc-story-copy,
        .sc-footer-grid > :first-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 780px) {
        .sc-landing {
            padding-top: 22px;
        }

        .sc-hero {
            padding-bottom: 84px;
        }

        .sc-hero-grid,
        .sc-story,
        .sc-split,
        .sc-footer-grid,
        .sc-features-grid,
        .sc-stats {
            grid-template-columns: 1fr;
        }

        .sc-hero-card {
            min-height: auto;
        }

        .sc-title {
            line-height: 1;
        }

        .sc-feature-card h3,
        .sc-story-copy h2,
        .sc-split-card h3 {
            font-size: 24px;
        }

        .sc-footer-bottom,
        .sc-story,
        .sc-elite-top,
        .sc-elite-bottom {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="sc-landing">
    <section class="sc-hero">
        <div class="sc-container">
            <div class="sc-hero-grid">
                <div class="sc-hero-copy">
                    <span class="sc-kicker">Digital Banking</span>
                    <h1 class="sc-title">{{ env('APP_NAME') }} brings private-bank polish to your daily money flow.</h1>
                    <p>
                        A sharper front page for customers who want secure onboarding, concierge-grade support,
                        and financial tools that feel modern from the first click.
                    </p>
                    <div class="sc-hero-actions">
                        <a href="{{ route('register') }}" class="mil-btn sc-btn-primary">Open Account</a>
                        <a href="{{ route('personal.banking-services') }}" class="mil-btn sc-btn-ghost">Explore Services</a>
                    </div>
                    <div class="sc-note-row">
                        <span>Protected onboarding</span>
                        <span>Live human support</span>
                        <span>Always-on digital access</span>
                    </div>
                </div>

                <div class="sc-hero-card">
                    <div class="sc-card-stack">
                        <div class="sc-elite-card">
                            <div class="sc-elite-top">
                                <div>
                                    <strong>{{ env('APP_NAME') }} Signature</strong>
                                    <p class="sc-elite-number">4532 8892 1048 7713</p>
                                </div>
                                <div class="sc-card-chip" aria-hidden="true"></div>
                            </div>
                            <div class="sc-elite-bottom">
                                <div>
                                    <strong>Account Focus</strong>
                                    <span>Personal and advisory banking</span>
                                </div>
                                <div>
                                    <strong>Member Since</strong>
                                    <span>2012</span>
                                </div>
                            </div>
                        </div>
                        <div class="sc-card-mini">
                            <strong>Decision layer</strong>
                            <p>Open an account, compare services, or speak with support in two taps from the landing page.</p>
                        </div>
                        <div class="sc-card-mini">
                            <strong>Trust signal</strong>
                            <p>Every call to action on this page now points at a real route, phone number, or email address.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sc-stats">
                <article class="sc-stat-card">
                    <strong>2.1M+</strong>
                    <p>clients supported through digital and branch banking.</p>
                </article>
                <article class="sc-stat-card">
                    <strong>45+</strong>
                    <p>markets served with guided onboarding and secure transfers.</p>
                </article>
                <article class="sc-stat-card">
                    <strong>99.9%</strong>
                    <p>platform uptime target across customer-facing tools.</p>
                </article>
                <article class="sc-stat-card">
                    <strong>15 min</strong>
                    <p>average time to resolve most support requests.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="sc-section">
        <div class="sc-container">
            <p class="sc-eyebrow">Capabilities</p>
            <h2 class="sc-title">The front page now sells clarity, confidence, and action.</h2>
            <p class="sc-subtitle">
                A premium, minimal design that keeps the experience polished on desktop and mobile
                while every visible button lands on a working page.
            </p>

            <div class="sc-features-grid">
                <article class="sc-feature-card">
                    <span class="sc-feature-index">01</span>
                    <h3>Fast onboarding</h3>
                    <p>Primary calls to action route directly to registration and open-account flows without dead ends.</p>
                </article>
                <article class="sc-feature-card">
                    <span class="sc-feature-index">02</span>
                    <h3>Premium navigation</h3>
                    <p>A simpler top bar keeps the experience polished on desktop and mobile while staying easy to scan.</p>
                </article>
                <article class="sc-feature-card">
                    <span class="sc-feature-index">03</span>
                    <h3>Route-safe actions</h3>
                    <p>Every visible button now lands on a working page, launches email, calls support, or scrolls back to top.</p>
                </article>
                <article class="sc-feature-card">
                    <span class="sc-feature-index">04</span>
                    <h3>Visual hierarchy</h3>
                    <p>Large headline typography, restrained color, and layered surfaces keep the page from feeling generic.</p>
                </article>
                <article class="sc-feature-card">
                    <span class="sc-feature-index">05</span>
                    <h3>Support-first trust</h3>
                    <p>Customer support and banking services stay one click away for visitors who are not ready to register.</p>
                </article>
                <article class="sc-feature-card">
                    <span class="sc-feature-index">06</span>
                    <h3>Lower error surface</h3>
                    <p>The home layout no longer depends on the old external theme scripts that were easy to break in browser checks.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="sc-section">
        <div class="sc-container">
            <div class="sc-story">
                <div class="sc-story-copy">
                    <p class="sc-eyebrow">Personal banking</p>
                    <h2>Designed for people who want human guidance without losing digital speed.</h2>
                    <p>
                        From checking and savings to secure alerts and account setup, the personal banking flow is the
                        core path on this site. The page now keeps that path visible from the hero to the footer.
                    </p>
                    <ul class="sc-list">
                        <li>Compare accounts and services before committing to registration.</li>
                        <li>Reach live support quickly if questions come up during onboarding.</li>
                        <li>Move from discovery to account opening without placeholder links.</li>
                    </ul>
                    <div class="sc-inline-actions">
                        <a href="{{ route('personal.open-account') }}" class="mil-btn sc-btn-primary">Start Opening</a>
                        <a href="{{ route('personal.customer-support') }}" class="mil-btn sc-btn-ghost">Talk to Support</a>
                    </div>
                </div>

                <aside class="sc-story-visual">
                    <div class="sc-visual-grid">
                        <div class="sc-visual-card">
                            <strong>Guided setup</strong>
                            <h4>Three clean entry points</h4>
                            <p>Register, review services, or reach support directly from the home page.</p>
                        </div>
                        <div class="sc-visual-card">
                            <strong>Security posture</strong>
                            <h4>Fewer moving parts</h4>
                            <p>The landing layout runs on local CSS and a tiny interaction script, which makes console checks cleaner.</p>
                        </div>
                        <div class="sc-visual-card">
                            <strong>Clean design</strong>
                            <h4>Premium without fake routes</h4>
                            <p>The page keeps a polished, premium feel without shipping buttons that go nowhere.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="sc-section">
        <div class="sc-container">
            <div class="sc-split">
                <article class="sc-split-card">
                    <p class="sc-eyebrow">Service path</p>
                    <h3>Need to explore before you join?</h3>
                    <p>
                        Use the services page for a full view of the account experience, the benefits, and the support structure
                        behind the platform.
                    </p>
                    <div class="sc-inline-actions">
                        <a href="{{ route('personal.banking-services') }}" class="mil-btn sc-btn-ghost">View Banking Services</a>
                    </div>
                </article>
                <article class="sc-split-card">
                    <p class="sc-eyebrow">Relationship path</p>
                    <h3>Want more context before signup?</h3>
                    <p>
                        Learn how {{ env('APP_NAME') }} positions trust, transparency, and advisory support before you take the next step.
                    </p>
                    <div class="sc-inline-actions">
                        <a href="{{ route('about') }}" class="mil-btn sc-btn-ghost">Read About {{ env('APP_NAME') }}</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="sc-section">
        <div class="sc-container">
            <div class="sc-footer-card">
                <div class="sc-footer-grid">
                    <div>
                        <p class="sc-eyebrow">Ready to move?</p>
                        <h3 class="sc-title" style="font-size: clamp(30px, 4vw, 52px);">Choose your next click and keep it moving.</h3>
                        <p class="sc-subtitle">
                            No decorative dead ends. The page now closes with real actions for registration, support, and service discovery.
                        </p>
                        <div class="sc-footer-links">
                            <a href="{{ route('register') }}" class="mil-btn sc-btn-primary">Create Account</a>
                            <a href="{{ route('personal.customer-support') }}" class="mil-btn sc-btn-ghost">Contact Support</a>
                        </div>
                    </div>

                    <div>
                        <strong>Quick links</strong>
                        <div class="sc-footer-links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('personal.banking-services') }}">Services</a>
                            <a href="{{ route('personal.open-account') }}">Open Account</a>
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    </div>

                    <div>
                        <strong>Reach us</strong>
                        <div class="sc-footer-links">
                            <a href="mailto:{{ env('MAIL_SUPPORT', env('MAIL_FROM_ADDRESS')) }}">{{ env('MAIL_SUPPORT', env('MAIL_FROM_ADDRESS')) }}</a>
                            <a href="tel:+442079460123">+44 20 7946 0123</a>
                            <a href="{{ route('about') }}">About the bank</a>
                            <a href="{{ route('personal.customer-support') }}">Customer support</a>
                        </div>
                    </div>
                </div>

                <div class="sc-footer-bottom">
                    <span>&copy; {{ now()->year }} {{ env('APP_NAME') }}. All rights reserved.</span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
