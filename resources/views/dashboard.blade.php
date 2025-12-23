{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . ($user->name ?? 'User'))

@section('content')

@if(session()->has('impersonator_id'))
    <form action="{{ route('admin.impersonate.leave') }}"
          method="POST"
          class="bg-yellow-200 border border-yellow-300 p-2 mb-4 text-center">
        @csrf
        <span class="text-sm text-yellow-800">
            You are logged in as this user
        </span>
        <button class="ml-3 text-blue-600 font-semibold">
            Return to Admin
        </button>
    </form>
@endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif --}}

            @if(session('limit_reached'))
                <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <strong>⚠️ Limit Reached!</strong> You have reached your link limit. 
                    @if(!$subscription || $subscription->status != 'active')
                        <a href="{{ route('pricing') }}" class="font-bold underline ml-2">Upgrade Now</a>
                    @else
                        <a href="{{ route('pricing') }}" class="font-bold underline ml-2">Buy Extra Links</a>
                    @endif
                </div>
            @endif

            @if(session('subscription_expired'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong>❌ Subscription Expired!</strong> Your subscription has expired. You can only create 5 links. 
                    <a href="{{ route('pricing') }}" class="font-bold underline ml-2">Renew Now</a>
                </div>
            @endif

            {{-- Variables Setup --}}
            @php
                // Use data passed from controller with proper fallbacks
                $planName = $planName ?? 'Free Plan';
                $planLimit = $planLimit ?? 5;
                $extraLinks = $extraLinks ?? 0;
                $totalLimit = $totalLimit ?? ($planLimit + $extraLinks);
                $activeLinksCount = $activeLinksCount ?? 0;
                $remainingLinks = $remainingLinks ?? max($totalLimit - $activeLinksCount, 0);
                $totalLinks = $totalLinks ?? $activeLinksCount;
                $totalUsagePercentage = $totalUsagePercentage ?? ($totalLimit > 0 ? min(100, ($activeLinksCount / $totalLimit) * 100) : 0);
                
                // Calculate breakdown
                $planLinksUsed = $planLinksUsed ?? min($activeLinksCount, $planLimit);
                $extraLinksUsed = $extraLinksUsed ?? max(0, $activeLinksCount - $planLimit);
                
                // Days remaining handling with proper fallback
                $daysRemaining = $daysRemaining ?? 0;
                $expiryDate = $expiryDate ?? null;
                
                // Can create more links
                $canCreateMoreLinks = $canCreateMoreLinks ?? ($activeLinksCount < $totalLimit);
            @endphp

            {{-- Subscription Info --}}
            @if($subscription && $subscription->status == 'active')
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg p-6 text-white mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">{{ $planName }}</h3>
                        <p class="text-blue-100">
                            @if($expiryDate)
                                @php
                                    $expiryDateFormatted = \Carbon\Carbon::parse($expiryDate);
                                    $daysRemaining = $daysRemaining ?? now()->diffInDays($expiryDateFormatted, false);
                                @endphp
                                
                                @if($daysRemaining > 0)
                                    Expires in {{ $daysRemaining }} days ({{ $expiryDateFormatted->format('M d, Y') }})
                                @else
                                    Expired on {{ $expiryDateFormatted->format('M d, Y') }}
                                @endif
                            @else
                                Active Subscription
                            @endif
                        </p>
                        
                        {{-- Extra Links Badge --}}
                        @if($extraLinks > 0)
                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500 text-white">
                            <i class="fas fa-plus mr-1"></i>
                            {{ $extraLinks }} Extra Links
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold">{{ $totalLimit == 0 ? 'Unlimited' : $totalLimit }} Links</p>
                        <p class="text-blue-100">Total Available</p>
                        @if($extraLinks > 0)
                        <p class="text-blue-100 text-sm">({{ $planLimit }} plan + {{ $extraLinks }} extra)</p>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-yellow-500 overflow-hidden shadow-sm sm:rounded-lg p-6 text-white mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">Free Plan</h3>
                        <p class="text-yellow-100">Upgrade to unlock more features</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold">{{ $planLimit == 0 ? 'Unlimited' : $planLimit }} Links</p>
                        <p class="text-yellow-100">Plan Limit</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Limit Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <i class="fas fa-layer-group text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Limit</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalLimit == 0 ? 'Unlimited' : $totalLimit }}</p>
                            @if($extraLinks > 0)
                            <p class="text-xs text-green-600">{{ $planLimit }} + {{ $extraLinks }} extra</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Active Links Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Links</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeLinksCount }}</p>
                            @if($extraLinks > 0 && $extraLinksUsed > 0)
                            <p class="text-xs text-gray-500">{{ $planLinksUsed }} plan + {{ $extraLinksUsed }} extra</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Remaining Links Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Remaining Links</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $remainingLinks }}</p>
                        </div>
                    </div>
                </div>

                {{-- Total Created Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 text-orange-500 mr-4">
                            <i class="fas fa-link text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Created</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalLinks }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Link Usage Progress</h3>
                    <span class="text-sm font-medium text-gray-600">{{ $activeLinksCount }}/{{ $totalLimit == 0 ? '∞' : $totalLimit }} ({{ round($totalUsagePercentage) }}%)</span>
                </div>
                
                {{-- Main Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                    <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                         style="width: {{ $totalLimit == 0 ? 100 : min(100, max(0, $totalUsagePercentage)) }}%"></div>
                </div>
                
                {{-- Breakdown Progress Bars --}}
                @if($extraLinks > 0)
                <div class="space-y-2 mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Plan Links: {{ $planLinksUsed }}/{{ $planLimit }}</span>
                        <span class="text-gray-600">{{ max(0, $planLimit - $planLinksUsed) }} remaining</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $planUsagePercentage = $planLimit > 0 ? min(100, ($planLinksUsed / $planLimit) * 100) : 0;
                        @endphp
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $planUsagePercentage }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Extra Links: {{ $extraLinksUsed }}/{{ $extraLinks }}</span>
                        <span class="text-gray-600">{{ max(0, $extraLinks - $extraLinksUsed) }} remaining</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $extraUsagePercentage = $extraLinks > 0 ? min(100, ($extraLinksUsed / $extraLinks) * 100) : 0;
                        @endphp
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $extraUsagePercentage }}%"></div>
                    </div>
                </div>
                @else
                <div class="flex justify-between text-sm text-gray-600 mt-2">
                    <span>{{ $activeLinksCount }} used</span>
                    <span>{{ $totalLimit == 0 ? 'Unlimited' : $remainingLinks }} remaining</span>
                </div>
                @endif
            </div>

           

         

            {{-- Account Information --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                        <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-user-edit mr-2"></i>Edit Profile
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- User Information --}}
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-gray-700 border-b pb-2">Profile Details</h4>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $user->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $user->email ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Account Created:</span>
                                <span class="font-medium">{{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}</span>
                            </div>
                        </div>

                        {{-- Subscription Information --}}
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-gray-700 border-b pb-2">Subscription Details</h4>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Plan:</span>
                                <span class="font-medium">{{ $planName }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Plan Limit:</span>
                                <span class="font-medium">{{ $planLimit == 0 ? 'Unlimited' : $planLimit }} Links</span>
                            </div>
                            @if($extraLinks > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Extra Links:</span>
                                <span class="font-medium text-green-600">+{{ $extraLinks }} Links</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Available:</span>
                                <span class="font-medium text-purple-600">{{ $totalLimit }} Links</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Active Links:</span>
                                <span class="font-medium">{{ $activeLinksCount }} / {{ $totalLimit == 0 ? 'Unlimited' : $totalLimit }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">
                                    @if($subscription && $subscription->status == 'active')
                                        <span class="text-green-600">Active</span>
                                        @if($daysRemaining > 0)
                                            ({{ $daysRemaining }} days remaining)
                                        @endif
                                    @else
                                        <span class="text-yellow-600">Free Plan</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>

        </div>
    </div>

    <script>
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.bg-green-200, .bg-red-100, .bg-yellow-100');
        alerts.forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
    </script>
@endsection