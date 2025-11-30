<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - LinkManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Header -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-crown text-yellow-500 mr-2"></i>
                            Admin Panel
                        </h1>
                    </div>
                    <!-- Desktop Menu -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 hover:text-gray-700">
                            <i class="fas fa-chart-line mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="{{ request()->routeIs('admin.users') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 hover:text-gray-700">
                            <i class="fas fa-users mr-2"></i>Users
                        </a>
                        <a href="{{ route('admin.plans') }}" 
                           class="{{ request()->routeIs('admin.plans') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 hover:text-gray-700">
                            <i class="fas fa-box mr-2"></i>Plans
                        </a>
                        <a href="{{ route('admin.assign-plan.form') }}" 
                           class="{{ request()->routeIs('admin.assign-plan*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 hover:text-gray-700">
                            <i class="fas fa-gift mr-2"></i>Assign Plan
                        </a>
                    </div>
                </div>
                
                <!-- User Info and Mobile Menu Button -->
                <div class="flex items-center">
                    <span class="hidden sm:inline text-gray-700 mr-4">
                        <i class="fas fa-user-shield mr-1"></i>
                        {{ auth()->user()->name }}
                    </span>
                    
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" 
                       class="{{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i>Users
                    </a>
                    <a href="{{ route('admin.plans') }}" 
                       class="{{ request()->routeIs('admin.plans') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-box mr-2"></i>Plans
                    </a>
                    <a href="{{ route('admin.assign-plan.form') }}" 
                       class="{{ request()->routeIs('admin.assign-plan*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-gift mr-2"></i>Assign Plan
                    </a>
                    
                    <!-- Mobile user info and logout -->
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-3 py-2">
                            <div class="text-sm font-medium text-gray-800">
                                <i class="fas fa-user-shield mr-1"></i>
                                {{ auth()->user()->name }}
                            </div>
                        </div>
                        <div class="mt-3 px-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Notifications -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Page Content -->
        <div class="bg-white shadow rounded-lg p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Overview</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Users</p>
                            <p class="text-xl font-bold text-gray-800">1,234</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-box text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Active Plans</p>
                            <p class="text-xl font-bold text-gray-800">567</p>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-gift text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Assignments</p>
                            <p class="text-xl font-bold text-gray-800">89</p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Growth</p>
                            <p class="text-xl font-bold text-gray-800">12.5%</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-medium text-gray-700 mb-3">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">New user registered: John Doe</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-green-100 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">Plan assignment completed successfully</p>
                            <p class="text-xs text-gray-500">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">System maintenance scheduled for tomorrow</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} LinkManager Admin Panel. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
            
            // Change icon based on menu state
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.className = 'fas fa-bars text-lg';
            } else {
                icon.className = 'fas fa-times text-lg';
            }
        });
        
        // Auto-hide notifications after 5 seconds
        setTimeout(() => {
            const notifications = document.querySelectorAll('.bg-green-100, .bg-red-100');
            notifications.forEach(notification => {
                notification.style.display = 'none';
            });
        }, 5000);
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = document.getElementById('mobile-menu-button');
            
            if (!mobileMenu.contains(event.target) && !menuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                menuButton.querySelector('i').className = 'fas fa-bars text-lg';
            }
        });
    </script>
</body>
</html>