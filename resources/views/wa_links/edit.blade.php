@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Edit WhatsApp Link
            </h2>
            <p class="text-sm text-gray-600 mt-1">Update your WhatsApp sharing link</p>
        </div>
        <a href="{{ route('wa-links.index') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            ← Back to Links
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    @if($errors->any())
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

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-900 to-black px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Edit Link Information</h3>
        </div>
        
        <form method="POST" action="{{ route('wa-links.update', $waLink) }}" class="p-6">
            @csrf
            @method('PUT')

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
                                   value="{{ old('name', $waLink->name) }}" 
                                   required
                                   class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">
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
                                   value="{{ old('slug', $waLink->slug) }}" 
                                   required
                                   class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Public URL: 
                            <span class="font-mono text-sm text-black font-semibold bg-gray-100 px-2 py-1 rounded">
                                {{ url('/') }}/<span id="slugPreview">{{ old('slug', $waLink->slug) }}</span>
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
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone', $waLink->phone) }}" 
                                   required
                                   placeholder="+91 9876543210"
                                   class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Keep country code. We'll normalize non-digits automatically.</p>
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
                                      class="pl-10 w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">{{ old('message', $waLink->message) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Toggle -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ $waLink->is_active ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                    peer-checked:after:translate-x-full peer-checked:after:border-white 
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-black">
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Active Link</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">When inactive, the link will not work for users</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function slugify(str) {
        return str.toString().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^A-Za-z\s-]/g,'').trim().replace(/\s+/g,'-').replace(/-+/g,'-').toLowerCase();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const nameEl = document.getElementById('name');
        const slugEl = document.getElementById('slug');
        const previewEl = document.getElementById('slugPreview');

        nameEl?.addEventListener('input', function () {
            const s = slugify(this.value);
            if (slugEl.dataset.auto === '1' || slugEl.value === '') {
                slugEl.value = s;
                previewEl.textContent = s;
                slugEl.dataset.auto = '1';
            }
        });

        slugEl?.addEventListener('input', function () {
            this.dataset.auto = '0';
            previewEl.textContent = this.value;
        });

        if (slugEl && nameEl) {
            slugEl.dataset.auto = slugify(nameEl.value) === slugEl.value ? '1' : '0';
        }
    });
</script>
@endsection