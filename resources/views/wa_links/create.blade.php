{{-- C:\Users\Tarun\wa-links-app\resources\views\wa_links\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create WhatsApp Link')

@section('page-title', 'Create WhatsApp Link')
@section('page-subtitle', 'Create a new WhatsApp sharing link')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Create WhatsApp Link
                </h2>
                <p class="text-sm text-gray-600 mt-1">Create a new WhatsApp sharing link</p>
            </div>
            <a href="{{ route('wa-links.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                ← Back to Links
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-400 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium opacity-90">Total Links</p>
                            <p class="text-2xl font-bold">{{ auth()->user()->waLinks()->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-400 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium opacity-90">Total Clicks</p>
                            <p class="text-2xl font-bold">{{ auth()->user()->waLinks()->sum('clicks') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-400 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium opacity-90">Active Links</p>
                            <p class="text-2xl font-bold">{{ auth()->user()->waLinks()->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Info -->
            @if(isset($remainingLinks))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">
                            You can create <strong>{{ $remainingLinks }}</strong> more links with your current plan.
                            @if($remainingLinks <= 2)
                                <a href="{{ route('pricing') }}" class="underline ml-2">Upgrade for more links</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-900 to-black px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Link Information</h3>
                </div>
                
                <form method="POST" action="{{ route('wa-links.store') }}" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Name Field -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Link Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="name" id="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="Enter a descriptive name"
                                           class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition" />
                                </div>
                            </div>

                            <!-- Slug Field -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    URL Slug
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="slug" id="slug"
                                           value="{{ old('slug') }}"
                                           required
                                           class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition" />
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Public URL: 
                                    <span class="font-mono text-sm text-black font-semibold bg-gray-100 px-2 py-1 rounded">
                                        {{ url('/') }}/<span id="slugPreview">{{ old('slug') }}</span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Phone Field -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    WhatsApp Number
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="phone"
                                           value="{{ old('phone') }}"
                                           required
                                           placeholder="+91 9876543210"
                                           class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition" />
                                </div>
                            </div>

                            <!-- Message Field -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Default Message
                                    <span class="text-gray-500 text-xs font-normal">(optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <textarea name="message" rows="4"
                                              placeholder="Enter a default message that will be pre-filled when users click the link"
                                              class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center pt-6 mt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
                        <div class="text-sm text-gray-500">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            All fields marked with <span class="text-red-500">*</span> are required
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('wa-links.index') }}"
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-3 bg-black text-white rounded-lg shadow hover:bg-gray-800 transition font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Link
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function slugify(str) {
            return str.toString()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^A-Za-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .toLowerCase();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const nameEl = document.getElementById('name');
            const slugEl = document.getElementById('slug');
            const preview = document.getElementById('slugPreview');

            nameEl.addEventListener('input', () => {
                if (!slugEl.dataset.manual) {
                    const s = slugify(nameEl.value);
                    slugEl.value = s;
                    preview.textContent = s;
                }
            });

            slugEl.addEventListener('input', () => {
                slugEl.dataset.manual = true;
                preview.textContent = slugEl.value;
            });
        });
    </script>
@endsection