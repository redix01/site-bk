<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Banko') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#0ea5e9',
                                600: '#0284c7',
                                700: '#0369a1',
                                800: '#075985',
                                900: '#0c4a6e',
                            },
                            dark: {
                                50: '#f8fafc',
                                100: '#f1f5f9',
                                200: '#e2e8f0',
                                300: '#cbd5e1',
                                400: '#94a3b8',
                                500: '#64748b',
                                600: '#475569',
                                700: '#334155',
                                800: '#1e293b',
                                900: '#0f172a',
                                950: '#020617',
                            }
                        }
                    }
                }
            }
        </script>
    @endif
    
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background-color: #020617 !important;
            color: #f1f5f9 !important;
        }
        
        /* Ensure dark theme is applied */
        .bg-dark-950 { background-color: #020617 !important; }
        .bg-dark-900 { background-color: #0f172a !important; }
        .bg-dark-800 { background-color: #1e293b !important; }
        .bg-dark-700 { background-color: #334155 !important; }
        .border-dark-800 { border-color: #1e293b !important; }
        .border-dark-700 { border-color: #334155 !important; }
        .text-gray-100 { color: #f1f5f9 !important; }
        .text-gray-300 { color: #cbd5e1 !important; }
        .text-gray-400 { color: #94a3b8 !important; }
        .text-white { color: #ffffff !important; }
        
        /* Sidebar styles */
        .sidebar-mobile {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 9999;
        }
        
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        
        /* Desktop sidebar always visible */
        @media (min-width: 1024px) {
            .sidebar-mobile {
                transform: translateX(0);
                position: relative;
                z-index: 1;
            }
        }
        
        .sidebar-overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            z-index: 9998;
        }
        
        .sidebar-overlay.open {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body class="bg-dark-950 text-gray-100 min-h-screen">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar-mobile fixed lg:relative inset-y-0 left-0 w-64 bg-dark-900 border-r border-dark-800 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-dark-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🏦</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">Banko</h1>
                            <p class="text-xs text-gray-400">Admin Panel</p>
                        </div>
                    </div>
                    <!-- Close button for mobile -->
                    <button id="sidebar-close-button" class="lg:hidden p-1 text-gray-400 hover:text-white hover:bg-dark-800 rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-300 hover:bg-dark-800 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-300 hover:bg-dark-800 hover:text-white transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span>Users</span>
                </a>
                
                <a href="{{ route('admin.transactions.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-300 hover:bg-dark-800 hover:text-white transition-colors {{ request()->routeIs('admin.transactions.*') ? 'bg-primary-600 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Transactions</span>
                </a>
                
                <a href="{{ route('admin.profile') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-300 hover:bg-dark-800 hover:text-white transition-colors {{ request()->routeIs('admin.profile') ? 'bg-primary-600 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>
            </nav>
            
            <!-- User Info -->
            <div class="p-4 border-t border-dark-800">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-dark-900 border-b border-dark-800 px-4 lg:px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-button" class="lg:hidden p-2 text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <div>
                            <h2 class="text-xl lg:text-2xl font-bold text-white">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-sm lg:text-base text-gray-400">@yield('page-description', 'Welcome to the admin panel')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L9 15l4.5 4.5L18 15l4.5 4.5"></path>
                            </svg>
                        </button>
                        
                        <!-- Settings -->
                        <button class="p-2 text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebarCloseButton = document.getElementById('sidebar-close-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            function toggleSidebar() {
                // Only toggle on mobile devices
                if (window.innerWidth < 1024) {
                    sidebar.classList.toggle('open');
                    sidebarOverlay.classList.toggle('open');
                }
            }
            
            function closeSidebar() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('open');
            }
            
            function isDesktop() {
                return window.innerWidth >= 1024;
            }
            
            // Initialize sidebar state
            function initializeSidebar() {
                if (isDesktop()) {
                    // Desktop: ensure sidebar is always open and visible
                    sidebar.classList.add('open');
                    sidebarOverlay.classList.remove('open');
                } else {
                    // Mobile: ensure sidebar starts closed
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('open');
                }
            }
            
            // Toggle sidebar when mobile menu button is clicked (mobile only)
            mobileMenuButton.addEventListener('click', function() {
                if (!isDesktop()) {
                    toggleSidebar();
                }
            });
            
            // Close sidebar when close button is clicked (mobile only)
            sidebarCloseButton.addEventListener('click', function() {
                if (!isDesktop()) {
                    closeSidebar();
                }
            });
            
            // Close sidebar when overlay is clicked (mobile only)
            sidebarOverlay.addEventListener('click', function() {
                if (!isDesktop()) {
                    closeSidebar();
                }
            });
            
            // Close sidebar when clicking on navigation links (mobile only)
            const navLinks = sidebar.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (!isDesktop()) {
                        closeSidebar();
                    }
                });
            });
            
            // Close sidebar on escape key (mobile only)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !isDesktop()) {
                    closeSidebar();
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                initializeSidebar();
            });
            
            // Initialize sidebar on page load
            initializeSidebar();
        });
    </script>
</body>
</html>
