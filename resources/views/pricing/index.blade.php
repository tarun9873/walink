@extends('pricing.base')

@section('content')
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
                                <!-- Paid Plan - DIRECT WhatsApp link -->
                                <a href="https://walive.link/pricing-buy" 
                                   target="_blank"
                                   class="block w-full whatsapp-bg-gradient
                                          text-white text-center py-4 px-6 rounded-xl font-bold text-lg shadow-lg transform transition-all duration-300 
                                          hover:scale-105 hover:shadow-xl">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    Buy Now
                                </a>
                                
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    Click to purchase on WhatsApp
                                </p>
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
@endsection