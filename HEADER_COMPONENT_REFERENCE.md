# Pages-3 Header Component Reference

## Overview
The `pages3-header.blade.php` component provides a reusable, maintainable header for all pages-3 templates. This component replaces inline header HTML across multiple view files.

## Component Location
```
resources/views/components/pages3-header.blade.php
```

## Usage
Simply include the component in any blade template:

```blade
<x-pages3-header />
```

## Features

### Dynamic Navigation Links
- All navigation links use Laravel's `route()` helper
- Links automatically point to the correct page based on route names
- No hardcoded URLs or relative paths

### Active Page Indicators
- Current page is indicated with `aria-current="page"` attribute
- Uses `request()->routeIs()` to detect active route
- Improves accessibility and UX

### Navigation Structure
The component includes:

1. **Top Bar Navigation**
   - Home link (pointing to `route('home')`)
   - Blog link (placeholder)
   - Language switcher (De, Fr, En)

2. **Mobile Services Menu**
   - Financial Info (placeholder)
   - Login eBanking (uses `route('login')`)
   - Language options

3. **Main Navigation Menu**
   - Our Company → `route('pages3.ourcompany')`
   - Media → `route('pages3.media')`
   - Investor Relations → `route('pages3.investorrelations')`
   - Private Banking → `route('pages3.privatebanking')`
   - International Banking → `route('pages3.internationalbanking')`
   - Institutional Clients → `route('pages3.institutionalclients')`
   - Asset Management → `route('pages3.assetmanagement')`

## Route Reference

All pages-3 routes use the `pages3` prefix and namespace:

```php
// Home sub-pages
Route::get('/asset-management', [Pages3Controller::class, 'homeAssetManagement'])->name('assetmanagement');
Route::get('/international-banking', [Pages3Controller::class, 'homeInternationalBanking'])->name('internationalbanking');
Route::get('/private-banking', [Pages3Controller::class, 'homePrivateBanking'])->name('privatebanking');
Route::get('/contact', [Pages3Controller::class, 'homeContact'])->name('contact');
Route::get('/our-company', [Pages3Controller::class, 'homeOurCompany'])->name('ourcompany');
Route::get('/media', [Pages3Controller::class, 'homeMedia'])->name('media');
Route::get('/investor-relations', [Pages3Controller::class, 'homeInvestorRelations'])->name('investorrelations');
Route::get('/institutional-clients', [Pages3Controller::class, 'homeInstitutionalClients'])->name('institutionalclients');
```

**Full route names:** `pages3.{routeName}`
Example: `pages3.ourcompany`, `pages3.media`, etc.

## Controller Methods

The `Pages3Controller` handles all view rendering:

```php
// In app/Http/Controllers/Pages3Controller.php

public function homeAssetManagement()
{
    return view('pages-3.home.asset-management');
}

public function homeInternationalBanking()
{
    return view('pages-3.home.international-banking');
}

public function homePrivateBanking()
{
    return view('pages-3.home.private-banking');
}

public function homeOurCompany()
{
    return view('pages-3.home.our-company');
}

public function homeMedia()
{
    return view('pages-3.home.media');
}

public function homeInvestorRelations()
{
    return view('pages-3.home.investor-relations');
}

public function homeInstitutionalClients()
{
    return view('pages-3.home.institutional-clients');
}

public function homeContact()
{
    return view('pages-3.home.contact');
}
```

## CSS Classes

The component maintains original ZKB styling:

- `c-header-public` - Main header container
- `c-header-public--is-zkb` - ZKB-specific styling
- `c-header-public__topbar` - Top navigation bar
- `c-header-public__mobile-wrap` - Mobile menu container
- `c-header-public__main-nav` - Main navigation list
- `c-icon` - Icon styling
- `c-button` - Button styling

## Accessibility Features

- Semantic HTML with proper `<header>` element
- `aria-current="page"` on active navigation items
- `aria-controls` attributes on menu toggles
- `aria-expanded` for toggle states
- Skip-to-content link available in main layout
- SVG icons with `<use>` references
- Proper button and link ARIA roles

## Logo and Branding

- Clickable ZKB logo links to home
- SVG-based logo (scalable, customizable)
- Dynamic branding support via `{{ config('app.name') }}` in other components

## Extending the Component

To add new navigation items:

1. Add new route to `routes/web.php` under pages3 prefix
2. Add corresponding method to `Pages3Controller`
3. Update header component with new `<li>` and `<a>` tag
4. Use `route('pages3.routename')` helper in link

Example:
```blade
<li class="c-header-public__nav-list-item" data-component-id="flyout-menu" data-component-is="flyout-menu">
    <a href="{{ route('pages3.newpage') }}" class="c-header-public__item c-header-public__main-nav-item" @if(request()->routeIs('pages3.newpage')) aria-current="page" @endif>New Page</a>
</li>
```

## Best Practices

1. **Always use `route()` helper** - Never hardcode URLs
2. **Maintain aria-current** - Keep active state indicators up-to-date
3. **Keep component DRY** - This centralized header prevents duplication
4. **Update in one place** - Changes apply to all pages automatically
5. **Test mobile menu** - Verify burger menu functionality on all pages

## Browser Support

The component uses:
- SVG for icons (IE9+)
- Flexbox for layouts (IE10+)
- CSS custom properties for theming (modern browsers)
- JavaScript for interactive menu (graceful degradation)

## Future Improvements

- Consider implementing language switching functionality
- Add mega-menu support for expandable categories
- Implement breadcrumb navigation
- Add search functionality
- Mobile-first responsive enhancements
