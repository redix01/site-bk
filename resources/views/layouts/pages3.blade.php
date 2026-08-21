<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>@yield('title', config('app.name') . ' – We are the bank that is close to you.')</title>
    
    <meta name="description" content="@yield('meta_description', 'We are one of the safest universal banks in the world with strong regional roots and an international network. We are committed to solving your financial needs.')"/>
    <meta name="template" content="@yield('meta_template', 'home')"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="robots" content="index,follow"/>
    
    <link rel="canonical" href="@yield('canonical', '/')"/>
    
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="@yield('og_url', '/')"/>
    <meta property="og:title" content="@yield('og_title', config('app.name') . ' – We are the bank that is close to you.')"/>
    <meta property="og:description" content="@yield('og_description', 'We are one of the safest universal banks in the world with strong regional roots and an international network.')"/>
    
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@zkb_ch"/>
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name') . ' – We are the bank that is close to you.')"/>
    <meta name="twitter:description" content="@yield('twitter_description', 'We are one of the safest universal banks in the world with strong regional roots and an international network.')"/>
    
    <meta name="datalayer" data-page-title="@yield('page_title', 'Home')" data-page-template="@yield('page_template', 'Home page')" data-page-path="@yield('page_path', '/')" data-internal-domains="www.zkb.ch" data-legacy-consent-enabled="false" data-gTag-enabled="true" data-segment="@yield('segment', 'Home')" data-override-report-suite="zuercherk.zkb.live" data-headline="@yield('headline', 'Home')" data-language="en" data-hierarchy="@yield('hierarchy', 'Home')" data-solution="ZKBCH" data-consent-enabled="true" data-country="ch" data-page-name-aem="@yield('page_name_aem', 'home')"/>
    
    <script nonce="h8Adt854peDZi6UXjT2BBt231qCLb81pyQntYiJTEXbjcFk=">
        (function () {
            const script = document.currentScript || document.querySelector('script[nonce]');
            const nonce = script?.nonce || script?.getAttribute('nonce') || '';

            window.adobeDataLayer = window.adobeDataLayer || [];
            window.adobeDataLayer.push({
                site: {
                    nonce: nonce
                }
            });
        })();
    </script>
    
    <script src="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-analytics.1767779646944.min.js"></script>
    <script src="https://www.zkb.ch/media/zkb/analytics/aep/9a98dc158ba7/140ae5e98596/launch-d1193b509421-22.min.js" data-ot-ignore async></script>
    
    <meta id="otHandler" data-ot-excluded="uptime-monitoring|Site24x7|www.google.com/bot|Lighthouse|Chrome/136.0.0.0" data-ot-domainseed="4fd60153-7345-4f7e-ba06-8a594ca9c375" data-ot-sdkstub="/media/zkb/offsys/cp/202503-1-0/oneTrust_production/scripttemplates/otSDKStub.js"/>

    <script nonce="h8Adt854peDZi6UXjT2BBt231qCLb81pyQntYiJTEXbjcFk=">
        function OptanonWrapper() {
            window.adobeDataLayer.push({
                event: 'OneTrustGroupsUpdated',
                eventInfo: {}
            });
        }
    </script>
    
    <link rel="stylesheet" href="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-zkb-ui.1767779646944.min.css" type="text/css">
    <link rel="stylesheet" href="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-consent-banner.1767779646944.min.css" type="text/css">
    <script src="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-zkb-ui.1767779646944.min.js"></script>
    <script src="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-consent-banner.1767779646944.min.js"></script>
    
    <link rel="stylesheet" href="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-site.1767779646944.min.css" type="text/css">
    
    <link rel="icon" href="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-site/resources/images/favicons/zkb/favicon.ico" sizes="48x48"/>
    <link rel="icon" href="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-site/resources/images/favicons/zkb/favicon.svg" sizes="any" type="image/svg+xml"/>
    <link rel="apple-touch-icon" href="https://www.zkb.ch/etc.clientlibs/zkb/clientlibs/all/clientlib-site/resources/images/favicons/zkb/apple-touch-icon.png"/>
    
    @yield('extra_head')
</head>
<body class="context-zkb">
    <a href="#main-content" class="c-skip-link">Skip to Main Content</a>

    <div class="l-page">
        {{-- Include the pages3 header component --}}
        <x-pages3-header />

        {{-- Main content area --}}
        <main id="main-content" class="l-page__main l-main-bezel-bottom l-page__main--bezel-inline l-max-width l-max-width--pineapple">
            @yield('content')
        </main>

        {{-- Footer can be added here in future --}}
    </div>

    @yield('extra_scripts')
</body>
</html>
