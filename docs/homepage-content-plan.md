# Homepage & About Us — Content Migration Plan

**Status:** Implemented. This copy now lives in [resources/views/layouts/bruk.blade.php](../resources/views/layouts/bruk.blade.php) (shared header/nav/footer), [resources/views/pages3/home.blade.php](../resources/views/pages3/home.blade.php) (`/`), and [resources/views/pages/about.blade.php](../resources/views/pages/about.blade.php) (`/about`). Bruk's assets were copied to [public/bruk/](../public/bruk/). See section 8 for what's still open.

## 1. What was studied

**Reference template:** `templates.hibotheme.com/bruk/default/` (local copy under `templates.hibotheme.com/bruk/default/` in this repo)
- `index-3.html` — "Home Digital Banking," the landing page variant Bruk itself uses for a banking product (as opposed to `index.html` = generic finance, `index-2.html` = business consulting). This is the one that matches our product.
- `about-us.html` — the About Us page and its section flow.
- Menu structure in both files (desktop `navbar-nav` + mobile `responsive-menu`).

**Current site in this codebase:**
- `/` → `Pages3Controller::home()` → [resources/views/pages3/home.blade.php](resources/views/pages3/home.blade.php) — **this file is an HTTrack mirror of the real Zürcher Kantonalbank site (zkb.ch)**, including their meta tags, analytics IDs, and copy. It should not stay in production as-is: it's a real, named financial institution's branding and content, not a template. Replacing it is the point of this exercise.
- `/about` → [resources/views/pages/about.blade.php](resources/views/pages/about.blade.php) — a separate, simpler About page.
- `/personal/*` → [resources/views/pages/personal/](resources/views/pages/personal/) — banking-services, open-account, customer-support (routed, live).
- `resources/views/pages2/*` (business/personal/wealth-management) — **not registered in `routes/web.php`**, effectively dead/orphaned mirror content. Not part of the live site.
- The actual product (from `routes/web.php`) is a personal digital-banking app: dashboard, transfers (internal + wire), deposits (incl. crypto), withdrawals, savings, investing, transaction history, profile/security settings. There is no business-banking module in the working app — so the new marketing site should sell what the product actually does, not invent a business-banking line we don't have.
- Brand name in code: `config('app.name')` falls back to **"Banko"** (see [resources/views/auth/login.blade.php:6](resources/views/auth/login.blade.php)). Used below as the placeholder brand name — swap for the real one if different.

## 2. What changes structurally

Drop from Bruk's template (doesn't fit a real banking product, or duplicates something the app already does):
- **Pricing Plan** section → the app doesn't sell subscription tiers; replaced with an **Account Types** section instead (Checking / Savings / Investing), which reuses the same 3-card layout.
- **Blog / News** section → no blog exists; replaced with a **Help & Security** 3-card teaser (Help Center, Security Center, FAQs) using the same card layout.
- **Success Stories / Case Studies** grid on About Us → reads as B2B portfolio work, not a bank's about page; replaced with an **Our Story milestones** strip.
- Template's "Services / Service Details / Projects / Team / Blog" mega-menu → collapsed into a menu that matches the app's actual sections.
- "97,000+ companies" partner-logo strip → optional; keep only if replaced with real security/compliance badges (FDIC/PCI/SSL-type marks), otherwise drop — don't fabricate fake partner logos.

Keep (same layout, new copy): Hero/Banner, Stats counters, Feature grid, About snippet, two image+text product blocks, Testimonials, App-download CTA, full footer, About page's Why-Choose-Us hero, 3-icon feature row, How-It-Works steps, stats, testimonials.

## 3. Global Header / Navigation

**Logo:** Banko

**Primary menu:**
| Label | Links to (existing route) |
|---|---|
| Home | `/` |
| About Us | `/about` |
| Personal Banking ▾ | dropdown below |
| ↳ Checking & Savings | `personal.banking-services` |
| ↳ Transfers & Payments | `transfer` (auth) |
| ↳ Deposits | `deposit` (auth) |
| ↳ Investing | `invest` (auth) |
| Support ▾ | dropdown below |
| ↳ Help Center | `personal.customer-support` |
| ↳ Security | new/placeholder |
| ↳ FAQs | new/placeholder |
| Contact Us | contact section/route |

**Header CTA buttons:** `Log In` (→ `login`) and `Open an Account` (→ `register`) — replaces Bruk's single "Get In Touch" button, since this is a functioning app with real auth, not a lead-gen site.

**Top utility bar (optional, from About Us header):** support phone, support email, and hours — use bracketed placeholders until the business supplies them: `[SUPPORT_PHONE]`, `[SUPPORT_EMAIL]`, `Mon–Fri [HOURS]`.

---

## 4. Homepage — section-by-section copy

### 4.1 Hero / Banner
- Eyebrow: `Digital Banking, Done Right`
- H1: `Banking That Moves As Fast As You Do`
- Subcopy: `Open an account in minutes, move money instantly, and grow your savings — all from one secure dashboard. No branch visits, no paperwork, no waiting.`
- Primary CTA: `Open an Account` → `register`
- Secondary link: `See How It Works →` (anchors to About Us or a How-It-Works section)
- Trust line (replaces "68 million+ Total Active user / Over 80+ countries"): `Trusted by [X]+ customers` / `Bank-grade encryption on every transaction`
- Image: reuse `assets/img/hero/hero-img-2.webp`, `hero-shape-1.webp`, `hero-shape-2.webp`, `card.webp` from the Bruk asset set as placeholders, or swap `card.webp` for a mock of the app's own card/dashboard UI later.

### 4.2 Stats bar (dark band, 4 counters)
Replace generic template stats with figures that describe the actual product (use real numbers once available; placeholders shown):
1. `[X]+` — Accounts opened
2. `$[X]M+` — Processed in transfers
3. `<[X]s` — Average transfer time
4. `24/7` — Account access & support

### 4.3 Feature section ("Best Digital Banking")
- Eyebrow: `Our Features`
- H2: `Everything Your Money Needs, In One App`
- Subcopy: `Banko brings checking, savings, transfers, and investing together — built around security, simplicity, and speed.`
- 4 feature cards (icon + title + one-line description):
  1. **Secure Payments** — `Every transfer is protected with encryption and real-time fraud monitoring.`
  2. **Effortless to Use** — `A clean dashboard that puts your balances, transfers, and history one tap away.`
  3. **Security First** — `Two-factor login, transaction PINs, and session controls keep your account yours.`
  4. **Low Fees** — `Transparent pricing with no hidden charges on everyday banking.`
- Bottom link: `Open an Account →`

### 4.4 Moving marquee strip
`ONLINE BANKING · MOBILE BANKING · SECURE TRANSFERS · SMART SAVINGS · EASY INVESTING`

### 4.5 About Us snippet
- Eyebrow: `About Us`
- H2: `Banking Built Around You`
- Subcopy: `Banko was built to make everyday banking simple — open an account online, manage your money from any device, and get support from real people when you need it.`
- 3 checklist items:
  1. **Instant Transfers** — `Send money between accounts or to other banks in seconds, not days.`
  2. **Smart Savings Tools** — `Set savings goals and track progress automatically.`
  3. **No Hidden Fees** — `Clear pricing on every account, every time.`
- Image: reuse `assets/img/about/about-img-2.webp` (or a dashboard mock later).

### 4.6 Trust strip (optional)
`Backed by [X]-bit encryption · [Deposit insurance / compliance badge] · [SOC 2 / PCI badge]` — placeholders; only ship if real badges exist. Otherwise remove this block rather than fabricate credentials.

### 4.7 Product highlight block 1 (image left, text right)
- Eyebrow: `Secure Transactions`
- H2: `Move Money With Confidence`
- Paragraph 1: `Every transfer — internal, wire, or crypto — runs through real-time verification and fraud checks, so your money moves safely every time.`
- Paragraph 2: `Track the status of every transaction from initiated to completed, with instant notifications along the way.`
- CTA: `Start a Transfer →`
- Image: reuse `assets/img/about/simple-img-1.webp`.

### 4.8 Product highlight block 2 (text left, image right)
- Eyebrow: `Your Accounts`
- H2: `One Dashboard, Every Account`
- Paragraph 1: `See your checking, savings, and investment balances in one place — updated in real time, no refreshing required.`
- Paragraph 2: `Download statements, review past transactions, and manage your profile and security settings without calling support.`
- CTA: `View Your Dashboard →` (routes to `dashboard` for logged-in users, `register` otherwise)
- Image: reuse `assets/img/about/simple-img-2.webp`.

### 4.9 Account Types (replaces Pricing Plan)
- Eyebrow: `Account Types`
- H2: `Choose the Account That Fits`
- 3 cards, same layout as pricing cards but no price tag:
  1. **Everyday Checking** — `Free everyday spending account with instant transfers and no minimum balance.` Features: no monthly fees · instant transfers · mobile deposits · debit access
  2. **High-Yield Savings** (marked "Most Popular," matching the "featured" card style) — `Grow your balance automatically with a competitive rate and goal-based savings tools.` Features: competitive APY · automatic round-ups · savings goals · no lock-in
  3. **Investing** — `Put your money to work with a guided, low-fee investing account.` Features: fractional investing · portfolio tracking · low fees · flexible withdrawals
- Each card CTA: `Open This Account →`

### 4.10 Testimonials
- Eyebrow: `Customer Stories`
- H2: `What Our Customers Say About Banking With Us`
- 3 short testimonial cards (name + role/city + 1–2 sentence quote). Use real customer quotes once available; until then mark as `[PENDING REAL TESTIMONIAL]` rather than inventing quotes attributed to fake named people.

### 4.11 App download CTA
Marquee: `BANK ON THE GO · BANK ON THE GO · BANK ON THE GO`
- Eyebrow: `Download Our App`
- H2: `Take Banko Wherever You Go`
- 3 checklist points:
  1. `Check balances and move money from your phone`
  2. `Get instant alerts on every transaction`
  3. `Message support directly from the app`
- App store badges: keep placeholders, link out only once the app actually exists in stores; otherwise hide the badges and keep the section as a "Coming Soon" note.

### 4.12 Help & Security (replaces Blog)
- Eyebrow: `Help & Security`
- H2: `Everything You Need to Bank Safely`
- 3 cards (replacing blog posts), each linking to a real support page:
  1. **Help Center** — `Answers to common questions about accounts, transfers, and fees.` → `personal.customer-support`
  2. **Security Center** — `How we protect your account and what you can do to stay safe.` → placeholder route
  3. **FAQs** — `Quick answers on opening an account, deposits, and more.` → placeholder route

### 4.13 Footer
- Column 1: Logo + one-line description: `Banko is a digital bank built for everyday people — simple accounts, fast transfers, and real support, wherever you are.` + social icons (only include the platforms actually used).
- Column 2 — **Quick Links:** About Us · Contact Us · Help Center · Security
- Column 3 — **Products:** Checking · Savings · Transfers · Investing · Crypto Deposits
- Column 4 — **Company:** Terms & Conditions · Privacy Policy · FAQs
- Column 5 — **Contact:** `[SUPPORT_EMAIL]` · `[SUPPORT_PHONE]` · `Mon–Fri [HOURS]` · `[BUSINESS_ADDRESS]`
- Newsletter bar: `Subscribe To Our Newsletter` / `Enter your email` / `Subscribe Now` (keep as-is, generic and safe).
- Copyright line: `© [YEAR] Banko. All rights reserved.` — remove the "proudly owned by HiboTheme" attribution entirely; that's the template vendor's, not ours.

---

## 5. About Us page — section-by-section copy

### 5.1 Breadcrumb / banner
- Breadcrumb: `Home / About Us`
- H1: `The Bank Built Around Your Life`
- (Bruk's own copy here — "Need Any Help? You can Contact With Us" — is really a Contact-page header that leaked into their About template; don't reuse it on About Us.)

### 5.2 Why Choose Us
- Eyebrow: `Why Choose Us`
- H2: `Modern Banking, Built On Trust`
- Paragraph 1: `We built Banko because everyday banking shouldn't mean long lines, confusing fees, or waiting days for your own money to move. Everything here is designed to be fast, clear, and secure.`
- Paragraph 2: `From your first deposit to your next investment, our team is focused on making banking feel simple again.`
- "Award winning" row → replace with **compliance/security badges** row: `[LICENSED/REGULATED BADGE]`, `[DEPOSIT INSURANCE BADGE]`, `[ENCRYPTION/SSL BADGE]` — placeholders only, populate with real credentials.
- Link: `View Our Products →`
- 3 feature cards (replacing Finance Investment / Sales Increase / Growing Business):
  1. **Secure & Insured** — `Your deposits and data are protected with industry-standard security.`
  2. **Transparent Pricing** — `No hidden fees — you always know what you're paying, and why.`
  3. **Real Support** — `Reach a real person by phone, email, or chat, any day of the week.`

### 5.3 Our Story (replaces "Success Stories" case-study grid)
- Eyebrow: `Our Story`
- H2: `How Banko Got Here`
- A short milestone strip instead of a portfolio grid, e.g.:
  - `[YEAR]` — `Banko launches with checking and savings accounts.`
  - `[YEAR]` — `Instant transfers and mobile deposits go live.`
  - `[YEAR]` — `Investing and crypto deposits added.`
  - `[YEAR]` — `[X]+ customers bank with us.`
  (Fill in real dates/milestones; placeholders until then.)

### 5.4 How It Works
- Eyebrow: `Getting Started`
- H2: `Open an Account In Three Steps`
- 3 steps (adapted from Bruk's generic "User Input / Data Retrieval / Transactions" flow to match actual onboarding):
  1. **Create Your Account** — `Sign up online in a few minutes — no branch visit required.`
  2. **Verify & Fund** — `Confirm your identity and make your first deposit securely.`
  3. **Start Banking** — `Transfer, save, and invest right from your dashboard.`

### 5.5 Stats
Reuse the homepage's stats block (accounts opened / volume transferred / average transfer time / support availability) so the numbers stay consistent site-wide.

### 5.6 Testimonials
Reuse the homepage testimonial section/content (same "pending real testimonial" rule applies).

### 5.7 Closing CTA
- H2: `Ready to Switch to Banko?`
- CTA: `Open an Account` → `register`

---

## 6. Image / asset notes

- Bruk's placeholder art (`assets/img/hero/*`, `about/*`, `app/*`, `breadcrumb/*`) is fine to use as **temporary stand-ins** during layout work, but none of it should ship to production — it's the vendor's stock art, not ours.
- Longer-term, swap: hero device/card mockups → real app dashboard screenshots; `about/award-*.webp` → real compliance badges or remove; `testimonials/client-*.webp` → real customer photos or generic avatars, never stock photos presented as real customers.
- Existing project images already in `public/img/home-1` through `home-5` may already cover some of this — worth checking before pulling anything new from Bruk.

## 7. What shipped

- `public/bruk/` — Bruk's `css/`, `js/`, `img/`, `fonts/` copied in wholesale (~19MB).
- [resources/views/layouts/bruk.blade.php](../resources/views/layouts/bruk.blade.php) — new shared layout: head/meta, preloader, dark-mode toggle, desktop navbar + offcanvas mobile menu, footer, back-to-top, scripts. Pulled in only by the two pages below — the ZKB-mirror sub-pages under `pages3/home/*` still use `layouts/pages3.blade.php` and were **not touched**.
- [resources/views/layouts/partials/bruk-nav.blade.php](../resources/views/layouts/partials/bruk-nav.blade.php) — desktop nav menu, shared by the layout.
- [resources/views/pages3/home.blade.php](../resources/views/pages3/home.blade.php) — full homepage rebuild per section 4 above.
- [resources/views/pages/about.blade.php](../resources/views/pages/about.blade.php) — full About Us rebuild per section 5 above, replacing the old `pages.layout.app_layout` (mil-/"Plax" theme) version. Other pages under `pages/personal/*` still use that theme and were **not touched** — expect a visual seam between About Us and those pages until they're migrated too.
- The vendor-branded raster logos (Bruk's own `logo.webp` literally reads "Bruk"; the pre-existing `public/img/logo.png` reads "Plax" — leftover ThemeForest template art, not real branding) were **not used**. The header/footer logo is now a text lockup driven by `config('app.name', 'Banko')`.
- All routes/links point at real, existing routes (`register`, `login`, `dashboard`, `transfer`, `deposit`, `invest`, `personal.banking-services`, `personal.customer-support`). No dead links were added.
- Verified locally: ran `composer install` (vendor was missing a package), ran migrations against a throwaway sqlite db, booted `php artisan serve`, and checked both pages render 200 with all `/bruk/*` assets loading and no console errors. The server was stopped after verification; the local `.env` and `database/database.sqlite` are gitignored and left in place for further local testing.

## 8. Still open / not done

- No real business details were invented — support phone/email/address, launch dates, customer counts, and testimonials are all placeholder text (`Pending Review`, `Support Email`, milestone years) and need real input before this goes live.
- Business Banking was intentionally **not** added as a nav item/section since the live app has no business-banking feature.
- The Bruk placeholder art (hero device mockups, stock photography, testimonial avatars) is still in place — swap for real product screenshots/photos before launch per section 6.
- The rest of the site (`pages3/home/*` sub-pages, `pages/personal/*`, dashboard) is still on its earlier themes (ZKB mirror / mil-"Plax" theme respectively) — only `/` and `/about` were migrated to Bruk in this pass.
