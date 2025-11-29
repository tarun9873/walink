<x-app-layout>
    <x-slot name="header">
        <div class="text-center">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full text-white text-sm font-medium mb-4">
                <i class="fas fa-crown mr-2"></i>
               Walive – WhatsApp Link Generator & QR Maker
            </div>
            <h2 class="font-bold text-4xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Pricing That Grows With You
            </h2>
            <p class="text-gray-600 mt-4 text-lg max-w-2xl mx-auto">Start free. Upgrade as you grow. No hidden fees.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Plans Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($plans as $plan)
                <div class="group relative">
                    <!-- Popular Badge -->
                    @if($plan->is_popular)
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg flex items-center space-x-2">
                            <i class="fas fa-fire"></i>
                            <span>MOST POPULAR</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden 
                               transform transition-all duration-500 hover:scale-105 hover:shadow-2xl
                               h-full flex flex-col
                               {{ $plan->is_popular ? 'border-2 border-yellow-400 shadow-2xl' : '' }}">
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
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
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
                            <a href="http://127.0.0.1:8000/pricing-buy" 
                               class="block w-full bg-gradient-to-r 
                                      @if($plan->sort_order == 1) from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700
                                      @elseif($plan->is_popular) from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600
                                      @else from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700
                                      @endif 
                                      text-white text-center py-4 px-6 rounded-xl font-bold text-lg shadow-lg transform transition-all duration-300 
                                      hover:scale-105 hover:shadow-xl">
                                <i class="fas 
                                    @if($plan->sort_order == 1) fa-user-plus
                                    @elseif($plan->is_popular) fa-bolt
                                    @else fa-crown
                                    @endif 
                                    mr-2"></i>
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- FAQ Section -->
            <div class="mt-20 max-w-4xl mx-auto">
                <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach([
                        ['q' => 'Can I change plans later?', 'a' => 'Yes, upgrade or downgrade anytime. Changes are prorated based on your current subscription.'],
                        ['q' => 'Is there a free trial?', 'a' => 'No free trials, but we offer a 14-day money-back guarantee if you\'re not satisfied.'],
                        ['q' => 'What payment methods?', 'a' => 'Credit cards, debit cards, UPI, net banking - all major payment methods accepted.'],
                        ['q' => 'How does link limit work?', 'a' => 'Each plan has specific WhatsApp link limits. Upgrade anytime for more links.'],
                        ['q' => 'Can I cancel anytime?', 'a' => 'Yes, cancel anytime. Access continues until the end of your billing period.'],
                        ['q' => 'Data security?', 'a' => 'Enterprise-grade security with encryption and regular backups.']
                    ] as $faq)
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 group cursor-pointer">
                        <h4 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition-colors flex items-center">
                            <i class="fas fa-question-circle text-blue-500 mr-3"></i>
                            {{ $faq['q'] }}
                        </h4>
                        <p class="text-gray-600 mt-3 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @media (max-width: 768px) {
            .grid-cols-1 {
                grid-template-columns: 1fr;
            }
            
            .max-w-6xl {
                max-width: 100%;
            }
            
            .p-8 {
                padding: 1.5rem;
            }
            
            .text-5xl {
                font-size: 3rem;
            }
        }
        
        @media (max-width: 640px) {
            .text-4xl {
                font-size: 2rem;
            }
            
            .text-5xl {
                font-size: 2.5rem;
            }
            
            .p-8 {
                padding: 1rem;
            }
        }
    </style>
</x-app-layout>