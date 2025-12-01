{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . ($user->name ?? 'User'))

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Subscription Info (UPDATED WITH EXTRA LINKS) --}}
            @php
                // Use data passed from controller
                $planName = $planName ?? 'Free Plan';
                $planLimit = $planLimit ?? 5;
                $extraLinks = $extraLinks ?? 0;
                $totalLimit = $totalLimit ?? ($planLimit + $extraLinks);
                $usedLinks = $usedLinks ?? 0;
                $remainingLinks = $remainingLinks ?? max($totalLimit - $usedLinks, 0);
                $totalLinks = $totalLinks ?? $usedLinks;
                $usagePercentage = $totalLimit > 0 ? ($usedLinks / max($totalLimit,1) * 100) : ($usedLinks ? 100 : 0);

                // Calculate breakdown
                $planLinksUsed = $planLinksUsed ?? min($usedLinks, $planLimit);
                $extraLinksUsed = $extraLinksUsed ?? max(0, $usedLinks - $planLimit);
            @endphp

            @if($subscription && $subscription->status == 'active')
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg p-6 text-white mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">{{ $planName }}</h3>
                        <p class="text-blue-100">
                            @if($daysRemaining && $expiryDate)
                                @if($daysRemaining > 0)
                                    Expires in {{ $daysRemaining }} days ({{ $expiryDate->format('M d, Y') }})
                                @else
                                    Expired on {{ $expiryDate->format('M d, Y') }}
                                @endif
                            @else
                                Subscription details not available
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

            {{-- Statistics Cards (UPDATED) --}}
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

                {{-- Used Links Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Links</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $usedLinks }}</p>
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

            {{-- Progress Bar (UPDATED) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Link Usage Progress</h3>
                    <span class="text-sm font-medium text-gray-600">{{ $usedLinks }}/{{ $totalLimit == 0 ? '∞' : $totalLimit }} ({{ round($totalUsagePercentage ?? $usagePercentage) }}%)</span>
                </div>
                
                {{-- Main Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                    <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                         style="width: {{ $totalLimit == 0 ? 100 : min(100, max(0, ($totalUsagePercentage ?? $usagePercentage))) }}%"></div>
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
                    <span>{{ $usedLinks }} used</span>
                    <span>{{ $totalLimit == 0 ? 'Unlimited' : $remainingLinks }} remaining</span>
                </div>
                @endif
            </div>

            {{-- Account Information (UPDATED) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                        @if($remainingLinks > 0 || $totalLimit == 0)
                        <a href="{{ route('wa-links.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-eye mr-2"></i>View All Links
                        </a>
                        @else
                        <button class="bg-gray-400 cursor-not-allowed text-white font-bold py-2 px-4 rounded" disabled>
                            <i class="fas fa-plus mr-2"></i>Plan Limit Reached
                        </button>
                        @endif
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

                        {{-- Subscription Information (UPDATED) --}}
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
                                <span class="text-gray-600">Links Used:</span>
                                <span class="font-medium">{{ $usedLinks }} / {{ $totalLimit == 0 ? 'Unlimited' : $totalLimit }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">
                                    @if($subscription && $subscription->status == 'active')
                                        <span class="text-green-600">Active</span>
                                    @else
                                        <span class="text-yellow-600">Free Plan</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">Quick Actions</h4>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profile
                            </a>
                            @if(!$subscription || $subscription->status != 'active')
                            <a href="{{ route('pricing') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-crown mr-2"></i>Upgrade Plan
                            </a>
                            @endif
                            @if($remainingLinks > 0 || $totalLimit == 0)
                            <a href="{{ route('wa-links.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-plus mr-2"></i>Create New Link
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection