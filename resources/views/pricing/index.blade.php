<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walive – WhatsApp Link Generator & QR Maker</title>
<!-- Primary meta tags -->
<meta name="google-site-verification" content="3i1t_WXk0eLPegLhAIBBAyd3F9D8BbJfy0Zz5wA2apw" />

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
                    <a href="/features" class="nav-link">About Us</a>
                    
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
                    <a href="/features" class="nav-link py-2">About Us</a>
                   
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

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 whatsapp-bg-gradient rounded-full text-white text-sm font-medium mb-4">
                    <i class="fab fa-whatsapp mr-2"></i>
                    <span>Walive – WhatsApp Link Generator & QR Maker</span>
                </div>
                <h1 class="font-bold text-4xl md:text-5xl whatsapp-text-gradient mb-4">
                    Pricing That Grows With You
                </h1>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto mb-8">Start free. Upgrade as you grow. No hidden fees.</p>
                
                <!-- Auth Status -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-6">
                    <a href="{{ route('login') }}" 
                       class="px-6 py-3 whatsapp-bg-gradient text-white rounded-lg hover:opacity-90 transition font-medium whatsapp-shadow">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login to Get Started
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-6 py-3 bg-white text-green-600 border border-green-600 rounded-lg hover:bg-green-50 transition font-medium">
                        <i class="fas fa-user-plus mr-2"></i>Create Free Account
                    </a>
                </div>
            </div>

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-20">
                @foreach($plans as $plan)
                <div class="group relative">
                    <!-- Popular Badge -->
                    @if($plan->is_popular)
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                        <div class="whatsapp-bg-gradient text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg flex items-center space-x-2">
                            <i class="fas fa-fire"></i>
                            <span>MOST POPULAR</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden 
                               transform transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl
                               h-full flex flex-col whatsapp-shadow
                               {{ $plan->is_popular ? 'border-2 border-green-500 shadow-2xl' : '' }}">
                        <div class="p-8 flex-1">
                            <!-- Plan Header -->
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                <p class="text-gray-500 mt-2">{{ $plan->description }}</p>
                            </div>
                            
                            <!-- Price -->
                            <div class="text-center mb-6">
                                <div class="flex items-baseline justify-center">
                                    <span class="text-5xl font-bold text-gray-900">₹{{ number_format($plan->price, 0) }}</span>
                                    <span class="text-gray-500 ml-2">/{{ $plan->billing_cycle }}</span>
                                </div>
                                <p class="text-gray-500 text-sm mt-2">
                                    {{ $plan->billing_cycle == 'year' ? 'Billed annually' : 'Billed monthly' }}
                                </p>
                            </div>
                            
                            <!-- Features from Database -->
                            <ul class="space-y-4 mb-8">
                                @php
                                    $features = is_array($plan->features) ? $plan->features : json_decode($plan->features, true);
                                    $features = $features ?? [];
                                @endphp
                                
                                @foreach($features as $feature)
                                <li class="flex items-center">
                                    <div class="flex-shrink-0 w-6 h-6" style="background-color: #dcf8c6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                        <i class="fas fa-check" style="color: var(--whatsapp-green); font-size: 0.75rem;"></i>
                                    </div>
                                    <span class="text-gray-700 {{ $plan->is_popular ? 'font-medium' : '' }}">
                                        {{ $feature }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <!-- CTA Button -->
                        <div class="p-8 pt-0">
                            @if($plan->price == 0)
                                <!-- Free Plan - Direct to dashboard -->
                                <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" 
                                   class="block w-full whatsapp-bg-gradient
                                          text-white text-center py-4 px-6 rounded-xl font-bold text-lg shadow-lg transform transition-all duration-300 
                                          hover:scale-105 hover:shadow-xl">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ auth()->check() ? 'Go to Dashboard' : 'Get Started Free' }}
                                </a>
                            @else
                                <!-- Paid Plan - Check auth -->
                                @auth
                                    <!-- User is logged in - Subscribe -->
                                    <form action="{{ route('subscribe', $plan) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full whatsapp-bg-gradient
                                                       text-white text-center py-4 px-6 rounded-xl font-bold text-lg shadow-lg transform transition-all duration-300 
                                                       hover:scale-105 hover:shadow-xl">
                                            <i class="fas fa-bolt mr-2"></i>
                                            Subscribe Now
                                        </button>
                                    </form>
                                @else
                                    <!-- User not logged in - Redirect to register -->
                                    <a href="https://walive.link/pricing-buy" 
                                       class="block w-full whatsapp-bg-gradient
                                              text-white text-center py-4 px-6 rounded-xl font-bold text-lg shadow-lg transform transition-all duration-300 
                                              hover:scale-105 hover:shadow-xl">
                                        <i class="fas fa-bolt mr-2"></i>
                                        Sign Up to Subscribe
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Testimonials -->
            <div class="mb-20">
                <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Loved by Businesses</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 whatsapp-bg-gradient rounded-full flex items-center justify-center text-white font-bold mr-4">
                                A
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Aarav Sharma</h4>
                                <p class="text-sm text-gray-600">Marketing Agency</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                        <p class="text-gray-700 italic">"Walive saved us hours of manual work. The link management is fantastic!"</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 whatsapp-bg-gradient rounded-full flex items-center justify-center text-white font-bold mr-4">
                                P
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Priya Patel</h4>
                                <p class="text-sm text-gray-600">E-commerce Store</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                        <p class="text-gray-700 italic">"Our customer engagement increased by 40% after using WhatsApp links."</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 whatsapp-bg-gradient rounded-full flex items-center justify-center text-white font-bold mr-4">
                                R
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Rohan Verma</h4>
                                <p class="text-sm text-gray-600">Freelancer</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                        <p class="text-gray-700 italic">"Simple, effective, and affordable. Perfect for small businesses."</p>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="max-w-4xl mx-auto mb-20">
                <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h4 class="font-bold text-lg text-gray-900 flex items-center">
                            <i class="fas fa-question-circle" style="color: var(--whatsapp-green); margin-right: 0.75rem;"></i>
                            Can I change plans later?
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">Yes, upgrade or downgrade anytime. Changes are prorated based on your current subscription.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h4 class="font-bold text-lg text-gray-900 flex items-center">
                            <i class="fas fa-question-circle" style="color: var(--whatsapp-green); margin-right: 0.75rem;"></i>
                            Is there a free trial?
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">No free trials, but we offer a 14-day money-back guarantee if you're not satisfied.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h4 class="font-bold text-lg text-gray-900 flex items-center">
                            <i class="fas fa-question-circle" style="color: var(--whatsapp-green); margin-right: 0.75rem;"></i>
                            What payment methods?
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">Credit cards, debit cards, UPI, net banking - all major payment methods accepted.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h4 class="font-bold text-lg text-gray-900 flex items-center">
                            <i class="fas fa-question-circle" style="color: var(--whatsapp-green); margin-right: 0.75rem;"></i>
                            How does link limit work?
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">Each plan has specific WhatsApp link limits. Upgrade anytime for more links.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h4 class="font-bold text-lg text-gray-900 flex items-center">
                            <i class="fas fa-question-circle" style="color: var(--whatsapp-green); margin-right: 0.75rem;"></i>
                            Can I cancel anytime?
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">Yes, cancel anytime. Access continues until the end of your billing period.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h4 class="font-bold text-lg text-gray-900 flex items-center">
                            <i class="fas fa-question-circle" style="color: var(--whatsapp-green); margin-right: 0.75rem;"></i>
                            Data security?
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">Enterprise-grade security with encryption and regular backups.</p>
                    </div>
                </div>
            </div>

            <!-- Final CTA -->
            <div class="text-center">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Ready to Boost Your WhatsApp Engagement?</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">Join thousands of businesses using Walive to connect with their customers.</p>
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                        <a href="{{ route('register') }}" 
                           class="px-8 py-4 whatsapp-bg-gradient text-white rounded-xl hover:opacity-90 transition font-bold text-lg shadow-lg">
                            Start Free Trial
                        </a>
                        <a href="{{ route('login') }}" 
                           class="px-8 py-4 bg-white text-green-600 border-2 border-green-600 rounded-xl hover:bg-green-50 transition font-bold text-lg">
                            Login to Account
                        </a>
                    </div>
                    <p class="text-sm text-gray-500 mt-6">No credit card required for free plan</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Simple Copyright Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} Walive. All rights reserved. | 
                    <a href="/privacy" class="text-green-600 hover:text-green-800">Privacy Policy</a> | 
                    <a href="/terms" class="text-green-600 hover:text-green-800">Terms of Service</a>
                </p>
                <p class="text-gray-500 text-xs mt-2">WhatsApp is a trademark of WhatsApp LLC. Walive is not affiliated with WhatsApp.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>