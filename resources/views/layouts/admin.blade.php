<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - LinkManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .notification-slide {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Main Container -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 flex-shrink-0 px-4 bg-gradient-to-r from-blue-600 to-purple-700">
                    <div class="flex items-center">
                        <i class="fas fa-crown text-yellow-300 text-xl mr-2"></i>
                        <span class="text-white text-xl font-bold">LinkManager</span>
                    </div>
                </div>
                
                <!-- Sidebar Navigation -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-4 py-4 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200">
                            <i class="fas fa-chart-line mr-3 text-gray-500 group-hover:text-blue-500 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : '' }}"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="{{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200">
                            <i class="fas fa-users mr-3 text-gray-500 group-hover:text-blue-500 {{ request()->routeIs('admin.users') ? 'text-blue-500' : '' }}"></i>
                            Users
                        </a>
                        <a href="{{ route('admin.plans') }}" 
                           class="{{ request()->routeIs('admin.plans') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200">
                            <i class="fas fa-box mr-3 text-gray-500 group-hover:text-blue-500 {{ request()->routeIs('admin.plans') ? 'text-blue-500' : '' }}"></i>
                            Plans
                        </a>
                        <a href="{{ route('admin.assign-plan.form') }}" 
                           class="{{ request()->routeIs('admin.assign-plan*') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border-l-4 transition-all duration-200">
                            <i class="fas fa-gift mr-3 text-gray-500 group-hover:text-blue-500 {{ request()->routeIs('admin.assign-plan*') ? 'text-blue-500' : '' }}"></i>
                            Assign Plan
                        </a>
                    </nav>
                    
                    <!-- User Profile Section -->
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-gray-500">Administrator</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Header -->
            <header class="glass-effect z-10 shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Mobile menu button and page title -->
                    <div class="flex items-center">
                        <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div class="ml-4 lg:ml-0">
                            <h1 class="text-xl font-semibold text-gray-900">
                                @yield('page-title', 'Dashboard')
                            </h1>
                        </div>
                    </div>
                    
                    <!-- User info for medium screens and up -->
                    <div class="hidden md:flex items-center">
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-bell"></i>
                            </button>
                            
                            <!-- User menu -->
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Mobile Sidebar -->
            <div id="mobile-sidebar" class="lg:hidden fixed inset-0 flex z-40 sidebar-transition transform -translate-x-full">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" id="mobile-sidebar-backdrop"></div>
                
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button id="mobile-sidebar-close" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <i class="fas fa-times text-white text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <!-- Mobile Sidebar Header -->
                        <div class="flex-shrink-0 flex items-center px-4">
                            <div class="flex items-center">
                                <i class="fas fa-crown text-yellow-500 text-xl mr-2"></i>
                                <span class="text-gray-900 text-xl font-bold">LinkManager</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Navigation -->
                        <nav class="mt-5 px-2 space-y-1">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-2 py-2 text-base font-medium rounded-md border-l-4">
                                <i class="fas fa-chart-line mr-4 text-gray-500 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : '' }}"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}" 
                               class="{{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-2 py-2 text-base font-medium rounded-md border-l-4">
                                <i class="fas fa-users mr-4 text-gray-500 {{ request()->routeIs('admin.users') ? 'text-blue-500' : '' }}"></i>
                                Users
                            </a>
                            <a href="{{ route('admin.plans') }}" 
                               class="{{ request()->routeIs('admin.plans') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-2 py-2 text-base font-medium rounded-md border-l-4">
                                <i class="fas fa-box mr-4 text-gray-500 {{ request()->routeIs('admin.plans') ? 'text-blue-500' : '' }}"></i>
                                Plans
                            </a>
                            <a href="{{ route('admin.assign-plan.form') }}" 
                               class="{{ request()->routeIs('admin.assign-plan*') ? 'bg-blue-50 text-blue-700 border-blue-500' : 'text-gray-600 hover:bg-gray-100' }} group flex items-center px-2 py-2 text-base font-medium rounded-md border-l-4">
                                <i class="fas fa-gift mr-4 text-gray-500 {{ request()->routeIs('admin.assign-plan*') ? 'text-blue-500' : '' }}"></i>
                                Assign Plan
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Mobile User Profile -->
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-gray-500">Administrator</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Notifications -->
                @if(session('success'))
                    <div class="notification-slide mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <button class="text-green-500 hover:text-green-600" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="notification-slide mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <button class="text-red-500 hover:text-red-600" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
            const mobileSidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');
            
            // Open mobile sidebar
            mobileMenuButton.addEventListener('click', function() {
                mobileSidebar.classList.remove('transform', '-translate-x-full');
                mobileSidebar.classList.add('transform', 'translate-x-0');
            });
            
            // Close mobile sidebar
            function closeMobileSidebar() {
                mobileSidebar.classList.remove('transform', 'translate-x-0');
                mobileSidebar.classList.add('transform', '-translate-x-full');
            }
            
            mobileSidebarClose.addEventListener('click', closeMobileSidebar);
            mobileSidebarBackdrop.addEventListener('click', closeMobileSidebar);
            
            // Auto-hide notifications after 5 seconds
            setTimeout(() => {
                const notifications = document.querySelectorAll('.notification-slide');
                notifications.forEach(notification => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 300);
                });
            }, 5000);
        });
    </script>
</body>
</html>