<nav x-data="{ open: false, userDropdown: false }" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Section -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
    <a href="{{ route(auth()->check() ? 'dashboard' : 'pricing') }}" class="flex items-center space-x-3 group">

        <!-- Logo Image -->
        <div class="images-logo">
            <img src="/images/downloa7_7484d.webp" 
                 alt="Walive Logo" 
                 class="block h-6 w-auto" style="height:60px;"/>
        </div>

    </a>
</div>


                <!-- Navigation Links -->
<div class="hidden sm:flex sm:items-center sm:ml-10 space-x-1">
    @auth
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 text-white hover:bg-white hover:bg-opacity-20 dark:hover:bg-gray-800">
            <i class="fas fa-tachometer-alt w-5 text-white"></i>
            <span>Dashboard</span>
        </x-nav-link>
        
        <x-nav-link :href="route('wa-links.index')" :active="request()->routeIs('wa-links.index')" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 text-white hover:bg-white hover:bg-opacity-20 dark:hover:bg-gray-800">
            <i class="fas fa-link"></i>
            <span>My Links</span>
        </x-nav-link>
    @endauth

                    @guest
                        <x-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-purple-50 dark:hover:bg-gray-800">
                            <i class="fas fa-crown text-purple-500"></i>
                            <span>Pricing</span>
                        </x-nav-link>
                    @endguest
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                @guest
                    <!-- Guest Navigation -->
                    
                @else

                @endguest

                <!-- User Dropdown -->
                <div class="relative ml-3" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" class="">
                            <div class="flex items-center space-x-3">
                                @auth
                                    <div class="hidden sm:block text-right">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                                    </div>
                                @else
                                    <div class="text-gray-600 dark:text-gray-300">Guest</div>
                                @endauth
                                
                                <!-- Avatar -->
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        @auth
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        @else
                                            G
                                        @endauth
                                    </div>
                                    @auth
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                    @endauth
                                </div>
                                
                                <!-- Dropdown Arrow -->
                                <div class="text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-xl bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                        
                        @auth
                            <!-- User Info -->
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="p-2 space-y-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                                    <i class="fas fa-tachometer-alt w-5 text-white"></i>
                                    <span>Dashboard</span>
                                </a>
                                
                                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                                    <i class="fas fa-user-edit w-5 text-green-500"></i>
                                    <span>Edit Profile</span>
                                </a>
                                
                                <a href="{{ route('wa-links.index') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                                    <i class="fas fa-link w-5 text-purple-500"></i>
                                    <span>My Links</span>
                                </a>

                                <!-- Divider -->
                                <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-3 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-150">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Guest Menu -->
                            <div class="p-2 space-y-1">
                                <a href="{{ route('login') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                                    <i class="fas fa-sign-in-alt w-5 text-blue-500"></i>
                                    <span>Sign In</span>
                                </a>
                                
                                @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                                    <i class="fas fa-user-plus w-5 text-green-500"></i>
                                    <span>Create Account</span>
                                </a>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="sm:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-3 px-4 py-3">
                    <i class="fas fa-tachometer-alt w-5 text-white text-blue-500 w-5"></i>
                    <span>Dashboard</span>
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('wa-links.index')" :active="request()->routeIs('wa-links.index')" class="flex items-center space-x-3 px-4 py-3">
                    <i class="fas fa-link text-green-500 w-5"></i>
                    <span>My Links</span>
                </x-responsive-nav-link>
                
                <a href="{{ route('wa-links.create') }}" class="flex items-center space-x-3 px-4 py-3 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 mx-3 rounded-lg">
                    <i class="fas fa-plus"></i>
                    <span>Create New Link</span>
                </a>
            @else
                <x-responsive-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" class="flex items-center space-x-3 px-4 py-3">
                    <i class="fas fa-crown text-purple-500 w-5"></i>
                    <span>Pricing</span>
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')" class="flex items-center space-x-3 px-4 py-3">
                    <i class="fas fa-sign-in-alt text-blue-500 w-5"></i>
                    <span>Sign In</span>
                </x-responsive-nav-link>
                
                @if(Route::has('register'))
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')" class="flex items-center space-x-3 px-4 py-3">
                    <i class="fas fa-user-plus text-green-500 w-5"></i>
                    <span>Get Started</span>
                </x-responsive-nav-link>
                @endif
            @endauth
        </div>
        
        @auth
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-4 space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-base font-medium text-gray-800 dark:text-white">{{ auth()->user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center space-x-3">
                    <i class="fas fa-user-edit text-gray-400 w-5"></i>
                    <span>Profile</span>
                </x-responsive-nav-link>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center space-x-3 text-red-600 dark:text-red-400">
                        <i class="fas fa-sign-out-alt text-red-400 w-5"></i>
                        <span>Sign Out</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">