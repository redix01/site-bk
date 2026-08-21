<!-- Desktop sidebar -->
<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
    <div class="flex items-center justify-between h-16 px-4 bg-gray-800">
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                <x-application-logo class="block h-8 w-auto fill-current text-gray-200" />
                <span class="ml-2 text-xl font-semibold">{{ config('app.name') }}</span>
            </a>
        </div>
        <button class="md:hidden text-gray-400 hover:text-white focus:outline-none" id="mobile-menu-close">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="h-full overflow-y-auto pt-4 pb-16">
        <div class="px-4 mb-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>
        
        <nav class="px-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.dashboard') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.users.*') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
            </a>
            
            <a href="{{ route('admin.transactions.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.transactions.*') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Transactions
            </a>
            
            <a href="{{ route('admin.reports') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.reports') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reports
            </a>
            
            <a href="{{ route('admin.activity-logs') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.activity-logs') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.activity-logs') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Activity Logs
            </a>
            
            <a href="{{ route('admin.settings') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.settings') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
            </a>
        </nav>
    </div>
    
    <div class="absolute bottom-0 w-full p-4 bg-gray-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="group flex items-center w-full px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Mobile menu button -->
<div class="md:hidden fixed top-0 left-0 right-0 z-30 bg-white shadow">
    <div class="flex items-center justify-between h-16 px-4">
        <div class="flex items-center">
            <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="ml-4 text-xl font-semibold text-gray-900">
                {{ config('app.name') }}
            </a>
        </div>
        <div class="flex items-center">
            <div class="relative ml-3">
                <div class="dropdown">
                    <button type="button" class="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                    </button>
                    <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">
                                Your Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" role="none">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile menu, show/hide based on menu state. -->
<div id="mobile-menu" class="fixed inset-0 z-40 hidden">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
    <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-gray-900 text-white">
        <div class="h-full overflow-y-auto">
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-200" />
                        <span class="ml-2 text-xl font-semibold">{{ config('app.name') }}</span>
                    </a>
                </div>
                <button class="text-gray-400 hover:text-white focus:outline-none" id="mobile-menu-close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <nav class="px-2 pt-2 pb-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.dashboard') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.users.*') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Users
                </a>
                
                <a href="{{ route('admin.transactions.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.transactions.*') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Transactions
                </a>
                
                <a href="{{ route('admin.reports') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.reports') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.reports') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Reports
                </a>
                
                <a href="{{ route('admin.activity-logs') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.activity-logs') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.activity-logs') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Activity Logs
                </a>
                
                <a href="{{ route('admin.settings') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.settings') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('admin.settings') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="group flex items-center w-full px-2 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                        <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </div>
</div>

<script>
    // Toggle mobile menu
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        }
        
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (mobileMenu && !mobileMenu.contains(event.target) && event.target !== mobileMenuButton) {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
</script>
