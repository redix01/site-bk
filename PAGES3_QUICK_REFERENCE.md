# Pages-3 Architecture Quick Reference

## Component Hierarchy

```
┌─────────────────────────────────────────────────────────┐
│           layouts/pages3.blade.php (Base)               │
│  ┌──────────────────────────────────────────────────┐   │
│  │ <head> with Meta Tags, CSS, JS                   │   │
│  └──────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ <x-pages3-header />                              │   │
│  │ ┌────────────────────────────────────────────┐  │   │
│  │ │ components/pages3-header.blade.php         │  │   │
│  │ │ - Top bar navigation                       │  │   │
│  │ │ - Mobile menu                              │  │   │
│  │ │ - Main navigation (7 categories)           │  │   │
│  │ │ - Dynamic route() links                    │  │   │
│  │ │ - Active page indicators                   │  │   │
│  │ └────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ <main> @yield('content')                         │   │
│  │ ↓                                                 │   │
│  │ Individual Page Content                          │   │
│  └──────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ </body></html>                                   │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘

        Individual page file
        e.g., pages-3/home/our-company.blade.php
        └── extends layouts/pages3
        └── @section('title', 'Our Company')
        └── @section('content') ... page HTML ... @endsection
```

## Navigation Link Flow

```
┌──────────────────────────────────────────────────────────────┐
│                  User Clicks Navigation Link                 │
│                    (e.g., "Our Company")                     │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│        Blade Component Renders Route Link                     │
│   {{ route('pages3.ourcompany') }}                           │
│                                                              │
│   Generates: /pages-3/our-company                           │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│      Routes (web.php) Matches Route                         │
│   Route::get('/our-company', [Pages3Controller::class,      │
│              'homeOurCompany'])->name('ourcompany');       │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│      Controller Method Handles Request                       │
│   Pages3Controller::homeOurCompany() {                       │
│       return view('pages-3.home.our-company');              │
│   }                                                          │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│      View Renders (extends layout)                          │
│   @extends('layouts.pages3')                                │
│   @section('content') ... page HTML ...                    │
│                                                              │
│   Layout includes:                                          │
│   - Header component with active state for current route    │
│   - Current page content                                    │
│   - Footer (if defined)                                    │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│         Complete HTML Page Sent to Browser                   │
│   Header automatically shows "Our Company" as active        │
└──────────────────────────────────────────────────────────────┘
```

## Header Component Structure

```
<x-pages3-header />
│
├─ <header class="c-header-public">
│  │
│  ├─ Top Bar Section
│  │  ├─ Home Link → route('home')
│  │  ├─ Blog Link → #
│  │  └─ Language Selector
│  │     ├─ De
│  │     └─ Fr
│  │
│  ├─ Logo & Burger Menu
│  │  ├─ ZKB Logo → route('home')
│  │  └─ Mobile Menu Toggle
│  │
│  └─ Mobile Services Menu
│     ├─ Financial Info → #
│     ├─ Login eBanking → route('login')
│     └─ Mobile Language Selector
│
└─ Main Navigation (Desktop)
   ├─ Our Company → route('pages3.ourcompany')
   ├─ Media → route('pages3.media')
   ├─ Investor Relations → route('pages3.investorrelations')
   ├─ Private Banking → route('pages3.privatebanking')
   ├─ International Banking → route('pages3.internationalbanking')
   ├─ Institutional Clients → route('pages3.institutionalclients')
   └─ Asset Management → route('pages3.assetmanagement')

All links include:
- Dynamic route generation via route()
- Active state indicator: @if(request()->routeIs(...))
- Proper ARIA labels for accessibility
```

## Route Structure

```
PAGES-3 PREFIX ROUTES
├─ /pages-3/ → pages3.home
├─ /pages-3/legal → pages3.legal
│
└─ Home Sub-Pages (pages3. prefix)
   ├─ /asset-management → pages3.assetmanagement
   ├─ /contact → pages3.contact
   ├─ /institutional-clients → pages3.institutionalclients
   ├─ /international-banking → pages3.internationalbanking
   ├─ /investor-relations → pages3.investorrelations
   ├─ /media → pages3.media
   ├─ /our-company → pages3.ourcompany
   ├─ /private-banking → pages3.privatebanking
   │
   └─ Legal Sub-Pages (pages3.legal. prefix)
      ├─ /legal/aeoi → pages3.legal.aeoi
      ├─ /legal/conflict-of-interest → pages3.legal.conflict-of-interest
      ├─ /legal/data-protection → pages3.legal.data-protection
      ├─ /legal/terms-conditions → pages3.legal.terms-conditions
      ├─ /legal/trading-and-investment-business → pages3.legal.trading-and-investment-business
      └─ /legal/whistleblowing → pages3.legal.whistleblowing

All routes controlled by:
app/Http/Controllers/Pages3Controller.php
```

## File Reference

### Key Files Created
- `resources/views/components/pages3-header.blade.php` - Header component
- `resources/views/layouts/pages3.blade.php` - Base layout
- `HEADER_COMPONENT_REFERENCE.md` - Detailed documentation
- `PAGES3_IMPLEMENTATION_SUMMARY.md` - Complete summary

### Key Files Modified  
- `resources/views/pages-3/home.blade.php` - Now uses component
- `routes/web.php` - Simplified route names
- `app/Http/Controllers/Pages3Controller.php` - Methods unchanged, routes simpler

### View Structure
```
resources/views/
├── pages-3/
│   ├── home.blade.php
│   ├── legal.blade.php
│   ├── lps/
│   │   ├── private-banking.blade.php
│   │   └── corporate/
│   │       └── berichterstattung.blade.php
│   └── home/
│       ├── our-company.blade.php       ← Uses pages3-header
│       ├── media.blade.php              ← 130+ similar files
│       ├── asset-management.blade.php
│       ├── contact.blade.php
│       ├── institutional-clients.blade.php
│       ├── international-banking.blade.php
│       ├── investor-relations.blade.php
│       └── private-banking.blade.php
├── components/
│   └── pages3-header.blade.php          ← NEW: Shared component
└── layouts/
    └── pages3.blade.php                 ← NEW: Base layout
```

## How to Use

### For Developers
1. All navigation links are in ONE component: `pages3-header.blade.php`
2. Change a route? It updates everywhere automatically
3. Add new page? Add route + method, then add link to header
4. Check routes with: `php artisan route:list | grep pages3`

### For Designers
1. Styling: Look in ZKB CSS files (linked from layout)
2. CSS Classes: See `HEADER_COMPONENT_REFERENCE.md`
3. Responsive: Mobile and desktop layouts in single file

### For Content Authors
1. Create new pages in `resources/views/pages-3/`
2. Extend the layout: `@extends('layouts.pages3')`
3. Add content: `@section('content') ... @endsection`
4. Add metadata: `@section('title', '...')` etc.

## Quick Commands

```bash
# View all pages-3 routes
php artisan route:list | grep pages3

# Generate correct route link
# In any blade file:
{{ route('pages3.ourcompany') }}

# Check current route in component
@if(request()->routeIs('pages3.ourcompany'))
    <!-- This shows when on Our Company page -->
@endif

# View all blade components
php artisan make:component --help

# Clear route cache after changes
php artisan route:clear
```

## Key Benefits Summary

✅ **Maintainability** - Single header source  
✅ **Flexibility** - Dynamic routing  
✅ **Reusability** - Component in any view  
✅ **Consistency** - Unified structure  
✅ **Accessibility** - Proper ARIA labels  
✅ **Performance** - Reduced duplication  
✅ **Scalability** - Easy to extend  
✅ **Documentation** - Comprehensive guides  

---
Last Updated: 2026-01-20  
Status: ✅ Complete
