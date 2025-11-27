<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Subscription Info (SAFE) --}}
            @php
                // Safe helpers: if $subscription is null, we fall back to defaults
                $plan = isset($subscription) ? ($subscription->plan ?? null) : null;
                $planName = $plan ? $plan->name : 'Free Plan';
                $planLimit = isset($planLimit) ? $planLimit : ($plan ? ($plan->links_limit ?? 0) : 0);
                $usedLinks = isset($usedLinks) ? $usedLinks : 0;
                $remainingLinks = max($planLimit - $usedLinks, 0);
                $totalLinks = isset($totalLinks) ? $totalLinks : $usedLinks;
                $usagePercentage = $planLimit > 0 ? ($usedLinks / max($planLimit,1) * 100) : ($usedLinks ? 100 : 0);

                // expiry data safe defaults (controller should set these when subscription exists)
                $daysRemaining = $daysRemaining ?? null;
                $expiryDate = $expiryDate ?? null;
            @endphp

            @if(isset($subscription) && $subscription)
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg p-6 text-white mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">{{ $planName }}</h3>
                        <p class="text-blue-100">
                            @if($daysRemaining !== null && $expiryDate !== null)
                                @if($daysRemaining > 0)
                                    Expires in {{ $daysRemaining }} days ({{ $expiryDate->format('M d, Y') }})
                                @else
                                    Expired on {{ $expiryDate->format('M d, Y') }}
                                @endif
                            @else
                                Subscription details not available
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold">{{ $planLimit == 0 ? 'Unlimited' : $planLimit }} Links</p>
                        <p class="text-blue-100">Plan Limit</p>
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
                {{-- Plan Limit Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <i class="fas fa-layer-group text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Plan Limit</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $planLimit == 0 ? 'Unlimited' : $planLimit }}</p>
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
                        </div>
                    </div>
                </div>

                {{-- Remaining Links Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
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
                    <span class="text-sm font-medium text-gray-600">{{ $usedLinks }}/{{ $planLimit == 0 ? '∞' : $planLimit }} ({{ round($usagePercentage) }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                         style="width: {{ $planLimit == 0 ? 100 : min(100, max(0, $usagePercentage)) }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-600 mt-2">
                    <span>{{ $usedLinks }} used</span>
                    <span>{{ $planLimit == 0 ? 'Unlimited' : $remainingLinks }} remaining</span>
                </div>
            </div>

            {{-- Account Information --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                        @if($remainingLinks > 0 || $planLimit == 0)
                        <a href="{{ route('wa-links.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>All Links Show
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
                                <span class="font-medium">{{ optional(Auth::user())->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ optional(Auth::user())->email ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Account Created:</span>
                                <span class="font-medium">{{ optional(Auth::user())->created_at ? optional(Auth::user())->created_at->format('M d, Y') : '—' }}</span>
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
                            <div class="flex justify-between">
                                <span class="text-gray-600">Links Used:</span>
                                <span class="font-medium">{{ $usedLinks }} / {{ $planLimit == 0 ? 'Unlimited' : $planLimit }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">
                                    @if(isset($subscription) && $subscription)
                                        @if(method_exists($subscription,'isValid') ? $subscription->isValid() : (isset($subscription->is_active) && $subscription->is_active))
                                            <span class="text-green-600">Active</span>
                                        @else
                                            <span class="text-red-600">Expired</span>
                                        @endif
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
                            @if(!isset($subscription) || !$subscription)
                            <a href="{{ route('pricing') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-crown mr-2"></i>Upgrade Plan
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
