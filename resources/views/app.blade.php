<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Theme: apply saved preference before first paint to avoid a flash. Light is the default. -->
        <script>
            (function () {
                try {
                    var stored = localStorage.getItem('theme');
                    var theme = stored === 'light' || stored === 'dark' ? stored : 'light';
                    document.documentElement.classList.toggle('dark', theme === 'dark');
                } catch (e) {}
            })();
        </script>

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased overflow-x-hidden overscroll-none h-full m-0 p-0">
        @inertia
    </body>
</html>


