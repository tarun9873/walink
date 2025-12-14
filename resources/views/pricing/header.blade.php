<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dynamic Title -->
    <title>@yield('title', 'Walive – WhatsApp Link Generator & QR Maker')</title>

    <!-- Dynamic Meta Description -->
    <meta name="description" content="@yield('meta_description', 'Walive: Free WhatsApp link generator and QR code maker. Create wa.me style short links, custom messages & QR codes.')"/>

    <!-- Dynamic Keywords -->
    <meta name="keywords" content="@yield('meta_keywords', 'WhatsApp link generator, WhatsApp QR code, wa.me link, Walive, WhatsApp link maker')" />

    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- OG -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('og_title', 'Walive – WhatsApp Link Generator & QR Code Maker')" />
    <meta property="og:description" content="@yield('og_description', 'Create WhatsApp links with custom messages & QR codes — fast, free, no signup')" />
    <meta property="og:image" content="@yield('og_image', 'https://walive.link/assets/og-image.png')" />
    <meta property="og:url" content="{{ url()->current() }}" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('twitter_title', 'Walive – WhatsApp Link Generator & QR Code Maker')" />
    <meta name="twitter:description" content="@yield('twitter_description', 'Create WhatsApp links with QR codes for business, sellers & creators')" />
    <meta name="twitter:image" content="@yield('twitter_image', 'https://walive.link/assets/og-image.png')" />

    <!-- All Favicons, PWA, Theme Color (static, OK) -->
    <link rel="icon" href="/images/iconwalive.webp" />
    <link rel="icon" type="image/x-icon" href="/images/iconwalive.webp">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#25D366">

    <!-- Custom per-page meta -->
    @stack('meta')



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
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* WhatsApp Colors */
        :root {
            --whatsapp-green: #25D366;
            --whatsapp-dark-green: #128C7E;
            --whatsapp-teal-green: #075E54;
            --whatsapp-light-green: #dcf8c6;
            --whatsapp-blue: #34B7F1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3);
            background: linear-gradient(135deg, var(--whatsapp-dark-green), var(--whatsapp-teal-green));
        }
        
        .nav-link {
            color: #4b5563;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--whatsapp-green);
        }
        
        .whatsapp-bg-gradient {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
        }
        
        .whatsapp-light-bg {
            background-color: #f0fff4;
        }
        
        .whatsapp-text-gradient {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .whatsapp-border {
            border-color: var(--whatsapp-green);
        }
        
        .whatsapp-shadow {
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
      <!-- Header/Navigation -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="images-logo">
                        <img src="/images/downloa7_7484d.webp" 
                             alt="Walive Logo" 
                             class="block h-6 w-auto" style="height:60px;"/>
                    </div>
                   
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{route('pricing')}}" class="nav-link">Home</a>
                    <a href="{{route('about')}}" class="nav-link">About Us</a>
                    
                    <a href="/blog" class="nav-link">Blog</a>
                    <a href="/contact" class="nav-link">Contact</a>
                    <!-- Auth Buttons -->
                    @auth
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-green-600">Dashboard</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-primary">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            
                            <a href="{{ route('register') }}" class="btn-primary">Get Started Free</a>
                        </div>
                    @endauth
                </nav>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden py-4 border-t border-gray-100">
                <div class="flex flex-col space-y-4">
                    <a href="{{route('pricing')}}" class="nav-link py-2">Home</a>
                    <a href="{{route('about')}}" class="nav-link py-2">About Us</a>
                   
                    <a href="/blog" class="nav-link py-2">Blog</a>
                     <a href="/contact" class="nav-link py-2">Contact</a>
                    @auth
                        <div class="pt-4 border-t border-gray-100">
                            <a href="{{ route('dashboard') }}" class="block py-2 text-gray-600">Dashboard</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();" class="block mt-2 btn-primary text-center">
                                Logout
                            </a>
                            <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    @else
                        <div class="pt-4 border-t border-gray-100">
                           
                            <a href="{{ route('register') }}" class="block mt-2 btn-primary text-center">Get Started Free</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>