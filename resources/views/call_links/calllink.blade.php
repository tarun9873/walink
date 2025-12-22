@extends('layouts.app')

@section('title', isset($callLink) ? 'Edit Call Link' : 'Create Call Link')

@section('page-title', isset($callLink) ? 'Edit Call Link' : 'Create Call Link')
@section('page-subtitle', isset($callLink) ? 'Edit your phone call link' : 'Create a new phone call link')

@push('styles')
<!-- Mobiscroll CSS -->
<link href="https://cdn.mobiscroll.com/5.21.0/css/mobiscroll.min.css" rel="stylesheet" />
<style>
.md-country-picker-item {
    position: relative;
    line-height: 20px;
    padding: 10px 0 10px 40px;
}

.md-country-picker-flag {
    position: absolute;
    left: 0;
    height: 20px;
}

.mbsc-scroller-wheel-item-2d .md-country-picker-item {
    transform: scale(1.1);
}

/* Custom styling to match your theme */
.mbsc-ios.mbsc-textfield-box {
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
}

.mbsc-ios.mbsc-textfield-box.mbsc-textfield-has-value {
    border-color: #3b82f6;
}

.mbsc-ios .mbsc-textfield-input {
    padding: 14px 12px;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ isset($callLink) ? 'Edit Call Link' : 'Create New Call Link' }}
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{ isset($callLink) ? 'Update your existing call link' : 'Create a direct phone call link to share with customers' }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.call-links.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Links
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg transform hover:-translate-y-1 transition-transform duration-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-400 bg-opacity-30 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Total Links</p>
                        <p class="text-2xl font-bold">{{ auth()->user()->callLinks()->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg transform hover:-translate-y-1 transition-transform duration-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-400 bg-opacity-30 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Total Calls</p>
                        <p class="text-2xl font-bold">{{ auth()->user()->callLinks()->sum('clicks') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg transform hover:-translate-y-1 transition-transform duration-200">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-400 bg-opacity-30 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Active Links</p>
                        <p class="text-2xl font-bold">{{ auth()->user()->callLinks()->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remaining Links Alert -->
        @if(isset($remainingLinks) && !isset($callLink))
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Links Available: {{ $remainingLinks }}</h4>
                            @if($remainingLinks <= 2)
                            <p class="mt-1 text-sm text-amber-600">
                                You're running low on links. 
                                <a href="{{ route('pricing') }}" class="font-medium text-blue-600 hover:text-blue-500 underline">
                                    Upgrade your plan
                                </a> for more.
                            </p>
                            @else
                            <p class="mt-1 text-sm text-blue-700">You can create {{ $remainingLinks }} more links with your current plan.</p>
                            @endif
                        </div>
                        @if($remainingLinks <= 2)
                        <a href="{{ route('pricing') }}" 
                           class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Upgrade Now
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-gray-900 to-black px-6 py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold text-white">
                                    {{ isset($callLink) ? 'Edit Call Link Details' : 'Call Link Information' }}
                                </h2>
                                <p class="text-sm text-gray-300">Fill in all required fields to create your call link</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <form action="{{ isset($callLink) ? route('admin.call-links.update', $callLink) : route('admin.call-links.store') }}" 
                          method="POST" 
                          class="p-6">
                        @csrf
                        @if(isset($callLink))
                            @method('PUT')
                        @endif

                        <!-- Link Name -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Link Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name', $callLink->name ?? '') }}" 
                                       required
                                       placeholder="e.g., Customer Support, Sales Hotline"
                                       class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('name') border-red-300 @enderror" />
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                Give your call link a descriptive name for easy identification
                            </p>
                        </div>

                        <!-- Phone Number Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <!-- Country Code with Mobiscroll -->
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Country Code <span class="text-red-500">*</span>
                                    </label>
                                    
                                    <label>
    Countries
    <input mbsc-input id="demo-country-picker" data-dropdown="true" data-input-style="box" data-label-style="stacked" placeholder="Please select..." />
</label>
  
                                    @error('country_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div class="md:col-span-8">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="phone" 
                                               id="phone"
                                               value="{{ old('phone', $callLink->phone ?? '') }}" 
                                               required
                                               placeholder="9876543210"
                                               class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('phone') border-red-300 @enderror" />
                                    </div>
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Enter the phone number that customers will call when they click the link
                            </p>
                        </div>

                        <!-- Custom URL Slug - WhatsApp Style -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Custom URL Slug <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="slug" 
                                       id="slug"
                                       value="{{ old('slug', $callLink->slug ?? '') }}" 
                                       required
                                       placeholder="support, sales, contact"
                                       class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('slug') border-red-300 @enderror" />
                            </div>
                            @error('slug')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview -->
                            <div class="mt-3 flex items-center text-sm text-gray-600 bg-gray-50 px-4 py-2.5 rounded-lg">
                                <span class="font-medium">Your link:</span>
                                <span class="mx-2">https://walive.link/call/</span>
                                <span id="slugPreview" class="text-blue-700 font-semibold">
                                    {{ old('slug', $callLink->slug ?? 'your-slug') }}
                                </span>
                            </div>
                        </div>

                        <!-- Active Status (Edit only) -->
                        @if(isset($callLink))
                        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $callLink->is_active) ? 'checked' : '' }}
                                       class="h-5 w-5 text-green-600 rounded focus:ring-green-500 border-gray-300">
                                <label for="is_active" class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-900">Active Status</span>
                                    <span class="block text-sm text-gray-600">When checked, this link will be publicly accessible</span>
                                </label>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="pt-6 mt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                                <div class="text-sm text-gray-500">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    All fields marked with <span class="text-red-500">*</span> are required
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.call-links.index') }}"
                                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium shadow-sm">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow hover:from-blue-700 hover:to-blue-800 transition font-medium flex items-center transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="{{ isset($callLink) ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 6v6m0 0v6m0-6h6m-6 0H6' }}" />
                                        </svg>
                                        {{ isset($callLink) ? 'Update Call Link' : 'Create Call Link' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Info & Steps -->
            <div class="lg:col-span-1">
                <!-- How It Works Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5">
                        <h3 class="text-lg font-bold text-white">How It Works</h3>
                    </div>
                    <div class="p-6">
                        <!-- Step 1 -->
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-sm">1</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-900">Create Your Link</h4>
                                    <p class="mt-1 text-sm text-gray-600">Fill in your phone number and customize the URL</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-bold text-sm">2</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-900">Share Everywhere</h4>
                                    <p class="mt-1 text-sm text-gray-600">Add to website, social media, emails, or business cards</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-purple-600 font-bold text-sm">3</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-900">Receive Calls</h4>
                                    <p class="mt-1 text-sm text-gray-600">Customers click to call you directly from any device</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pro Tips Card -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border border-amber-200 shadow-lg p-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-amber-900">Pro Tips</h4>
                            <ul class="mt-3 space-y-2">
                                <li class="flex items-start text-sm">
                                    <span class="text-amber-500 font-bold mr-2">•</span>
                                    <span class="text-amber-800">Use descriptive names for different departments</span>
                                </li>
                                <li class="flex items-start text-sm">
                                    <span class="text-amber-500 font-bold mr-2">•</span>
                                    <span class="text-amber-800">Create unique slugs for easy tracking</span>
                                </li>
                                <li class="flex items-start text-sm">
                                    <span class="text-amber-500 font-bold mr-2">•</span>
                                    <span class="text-amber-800">Add to email signatures and business cards</span>
                                </li>
                                <li class="flex items-start text-sm">
                                    <span class="text-amber-500 font-bold mr-2">•</span>
                                    <span class="text-amber-800">Monitor analytics to optimize performance</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.mobiscroll.com/5.21.0/js/mobiscroll.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    mobiscroll.setOptions({
        theme: 'ios',
        themeVariant: 'light'
    });

    const dialCodeMap = {
        in: '91',
        us: '1',
        gb: '44',
        ae: '971',
        au: '61'
    };

    const picker = mobiscroll.select('#demo-country-picker', {
        display: 'anchored',
        filter: true,
        itemHeight: 40,

        renderItem: function (item) {
            return `
                <div class="md-country-picker-item">
                    <img class="md-country-picker-flag"
                         src="https://img.mobiscroll.com/demos/flags/${item.value}.png">
                    ${item.text}
                </div>
            `;
        },

        onSet: function (ev) {
            document.getElementById('country_code_hidden').value =
                dialCodeMap[ev.value] || '';
        }
    });

    // Load country list
    mobiscroll.getJson(
        'https://trial.mobiscroll.com/content/countries.json',
        function (resp) {
            const countries = resp.map(c => ({
                text: c.text,
                value: c.value
            }));

            picker.setOptions({ data: countries });

            // EDIT MODE support
            @if(isset($callLink) && $callLink->country_code)
                const reverseMap = {
                    '91': 'in',
                    '1': 'us',
                    '44': 'gb',
                    '971': 'ae',
                    '61': 'au'
                };

                const val = reverseMap['{{ $callLink->country_code }}'];
                if (val) picker.setVal(val);
            @endif
        }
    );

});
</script>
@endpush
