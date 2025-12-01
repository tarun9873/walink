{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />
 
    
 <title>Walive – WhatsApp Link Generator & QR Maker</title>


<meta name="description" content="Walive: Free WhatsApp link generator and QR code maker. Create wa.me style short links, custom pre-filled messages and QR codes — share on Instagram, websites, product pages and marketing." />
<meta name="keywords" content="WhatsApp link generator, WhatsApp short link, wa.me, wa.link alternative, WhatsApp QR code, WhatsApp click to chat, WhatsApp link maker, Walive" />
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<link rel="canonical" href="https://walive.link/" />

<!-- Viewport & mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="format-detection" content="telephone=no">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta property="og:title" content="Walive – WhatsApp Link Generator & QR Code Maker" />
<meta property="og:description" content="Create wa.me-style WhatsApp links with custom pre-filled messages and QR codes. Fast, free, no signup." />
<meta property="og:url" content="https://walive.link/" />
<meta property="og:image" content="https://walive.link/assets/og-image.png" />
<meta property="og:image:alt" content="Walive - WhatsApp Link Generator & QR Code Maker" />
<meta property="og:site_name" content="Walive" />

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@Walive" />
<meta name="twitter:creator" content="@Walive" />
<meta name="twitter:title" content="Walive – WhatsApp Link Generator & QR Code Maker" />
<meta name="twitter:description" content="Create WhatsApp links with custom messages and QR codes. Perfect for businesses, sellers and creators." />
<meta name="twitter:image" content="https://walive.link/assets/og-image.png" />

<!-- Canonical alternatives / hreflang (example for English + Hindi) -->
<link rel="alternate" href="https://walive.link/" hreflang="en" />
<link rel="alternate" href="https://walive.link/hi/" hreflang="hi" />
<link rel="alternate" href="https://walive.link/" hreflang="x-default" />

<!-- Favicons -->
<link rel="icon" href="/images/iconwalive.webp" />
<!-- Favicon (Browser Icon) -->
<link rel="icon" type="image/x-icon" href="/images/iconwalive.webp">

<!-- PNG Favicons -->
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">

<!-- Apple Touch Icon (iPhone, iPad) -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

<!-- Android & Chrome -->
<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">

<!-- Manifest (PWA support) -->
<link rel="manifest" href="/site.webmanifest">

<!-- Safari Pinned Tab (optional) -->
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#25D366">

<meta name="theme-color" content="#25D366">


<!-- Sitemap (helps crawlers find pages) -->
<link rel="sitemap" type="application/xml" title="Sitemap" href="https://walive.link/sitemap.xml" />



<!-- Schema (basic) -->
<script type="application/ld+json">
{
"@context":"https://schema.org",
"@type":"WebSite",
"name":"Walive",
"url":"https://walive.link/",
"description":"Create WhatsApp short links, custom messages, and QR codes instantly. A perfect alternative to wa.link."
}
</script>
    
    <!-- Modern UI Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Your existing CSS styles */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f8fafc;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            position: fixed;
            height: 100vh;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: 260px;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 4px 12px;
            border-radius: 10px;
            color: #94a3b8;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.2));
            color: white;
            border-left: 4px solid var(--primary);
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
        }

        .progress-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .avatar-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .table-modern {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .table-row {
            transition: background 0.2s ease;
        }

        .table-row:hover {
            background: #f8fafc;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .mobile-menu-btn {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 100;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Tere existing assets -->
    @if (file_exists(public_path('mix-manifest.json')))
        <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
        <script src="{{ mix('js/app.js') }}" defer></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="mobile-menu-btn lg:hidden">
        <i class="fas fa-bars text-gray-700"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="closeSidebar()"></div>

    <!-- Dashboard Layout -->
    <div class="flex">
        @auth
        <!-- Sidebar (Only show when authenticated) -->
        <div id="sidebar" class="sidebar">
            <!-- User Profile -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="avatar-gradient w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(optional(auth()->user())->name, 0, 1) ?? 'U' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white truncate">{{ optional(auth()->user())->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ optional(auth()->user())->email ?? 'email@example.com' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="py-4">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('wa-links.index') }}" class="nav-item {{ request()->routeIs('wa-links.*') ? 'active' : '' }}">
                    <i class="fas fa-link w-5 mr-3"></i>
                    <span>My Links</span>
                    <span class="ml-auto bg-blue-500 text-xs px-2 py-1 rounded-full">{{ optional(auth()->user())->waLinks()->count() ?? 0 }}</span>
                </a>
                <a href="{{ route('wa-links.create') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit w-5 mr-3"></i>
                    <span>Create New Link</span>
                </a>
                 <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit w-5 mr-3"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('pricing') }}" class="nav-item {{ request()->routeIs('pricing') ? 'active' : '' }}">
                    <i class="fas fa-crown w-5 mr-3"></i>
                    <span>Upgrade</span>
                </a>
                
                <!-- Admin Links -->
                @if(auth()->check() && auth()->user()->is_admin)
                <div class="px-4 pt-6 pb-2">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Admin</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt w-5 mr-3"></i>
                    <span>Admin Panel</span>
                </a>
                @endif
            </nav>

            <!-- Plan Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
                <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-300">Plan</span>
                        <span class="text-xs bg-green-500 px-2 py-1 rounded">Pro</span>
                    </div>
                    <div class="mb-3">
                        @php
                            $user = auth()->user();
                            $totalLinks = optional($user)->remaining_links + optional($user)->waLinks()->count();
                            $usedLinks = optional($user)->waLinks()->count() ?? 0;
                            $percentage = $totalLinks > 0 ? ($usedLinks / $totalLinks * 100) : 0;
                        @endphp
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-400">Links Used</span>
                            <span class="text-gray-300">{{ $usedLinks }}/{{ $totalLinks }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth

        <!-- Main Content -->
        <div id="main-content" class="main-content flex-1 min-h-screen {{ auth()->check() ? '' : 'ml-0' }}">
            <!-- Top Bar -->
            @auth
            <header class="bg-white border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                           <div class="images-logo">
                        <img src="/images/downloa7_7484d.webp" 
                             alt="Walive Logo" 
                             class="block h-6 w-auto" style="height: 59px;width: 114px;"/>
                    </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            
                            
                            <!-- Quick Stats -->
                            <div class="hidden md:flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ optional(auth()->user())->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ optional(auth()->user())->remaining_links ?? 0 }} links remaining</p>
                                </div>
                                <div class="avatar-gradient w-10 h-10 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr(optional(auth()->user())->name, 0, 1) ?? 'U' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @endauth

            <!-- Notifications -->
            <div class="px-6 pt-6">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content -->
            <main class="p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6 mt-12">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                    <p>© {{ date('Y') }} Walive. All rights reserved.</p>
                    <div class="flex items-center space-x-4 mt-2 md:mt-0">
                        <a href="#" class="hover:text-blue-600">Privacy</a>
                        <a href="#" class="hover:text-blue-600">Terms</a>
                        <a href="#" class="hover:text-blue-600">Help</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Mobile Sidebar Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        
        function openSidebar() {
            if (sidebar) {
                sidebar.classList.add('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('hidden');
            }
        }
        
        function closeSidebar() {
            if (sidebar) {
                sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.add('hidden');
            }
        }
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (sidebar && !sidebar.contains(e.target) && mobileMenuBtn && !mobileMenuBtn.contains(e.target)) {
                    closeSidebar();
                }
            }
        });
    </script>
</body>
</html>