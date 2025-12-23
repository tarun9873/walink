@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Analytics - {{ $waLink->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">Detailed click analytics for your WhatsApp link</p>
        </div>
        <a href="{{ route('wa-links.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
            Back to Links
        </a>
    </div>
@endsection

@section('content')
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-xl">
                    <i class="fas fa-mouse-pointer text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Clicks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalClicks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-xl">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unique Visitors</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $uniqueVisitors }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-xl">
                    <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tracking Since</p>
                    <p class="text-lg font-bold text-gray-900">{{ $waLink->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-xl">
                    <i class="fas fa-link text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Link Status</p>
                    <p class="text-lg font-bold {{ $waLink->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $waLink->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Clicks Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Daily Clicks -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Daily Click History</h3>
            </div>
            <div class="p-6">
                @if($dailyClicks->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach($dailyClicks as $click)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($click->date)->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($click->date)->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-blue-600">{{ $click->count }}</p>
                                <p class="text-xs text-gray-500">clicks</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No click data available yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Country & Device Stats -->
        <div class="space-y-8">
            <!-- Country Distribution -->
          <!-- City Distribution -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-8">
    <div class="bg-gradient-to-r from-indigo-900 to-indigo-700 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Clicks by City</h3>
    </div>

    <div class="p-6">
        @if($cityClicks->count() > 0)
            <div class="space-y-3">
                @foreach($cityClicks as $city)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-city text-gray-400"></i>
                            <span class="font-medium text-gray-700">
                                {{ $city->city }}
                            </span>
                        </div>
                        <span class="font-bold text-indigo-600">
                            {{ $city->count }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center">No city data available</p>
        @endif
    </div>
</div>


            <!-- Device Distribution -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-900 to-purple-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Clicks by Device</h3>
                </div>
                <div class="p-6">
                    @if($deviceClicks->count() > 0)
                        <div class="space-y-3">
                            @foreach($deviceClicks as $device)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-mobile-alt text-gray-400"></i>
                                    <span class="font-medium text-gray-700">{{ $device->device_type ?: 'Unknown' }}</span>
                                </div>
                                <span class="font-bold text-purple-600">{{ $device->count }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No device data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Link Information -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-900 to-black px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Link Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Basic Info</h4>
                    <div class="space-y-2">
                        <p><strong>Name:</strong> {{ $waLink->name }}</p>
                        <p><strong>Slug:</strong> {{ $waLink->slug }}</p>
                        <p><strong>Phone:</strong> {{ $waLink->phone }}</p>
                        <p><strong>Status:</strong> 
                            <span class="{{ $waLink->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $waLink->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Your Link</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <code class="text-sm break-all">https://walive.link/{{ $waLink->slug }}</code>
                    </div>
                    <a href="{{ url($waLink->slug) }}" target="_blank" 
                       class="inline-flex items-center mt-3 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Test your link
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush