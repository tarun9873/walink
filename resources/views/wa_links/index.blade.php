@extends('layouts.app')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                WhatsApp Links
            </h2>
            <p class="text-sm text-gray-600 mt-1">Manage your WhatsApp sharing links</p>
        </div>
        <a href="{{ route('wa-links.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow w-full sm:w-auto justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create New
        </a>
    </div>
@endsection

@section('content')
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-2 md:p-3 bg-blue-100 rounded-lg md:rounded-xl">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-600">Total Links</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $links->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-2 md:p-3 bg-green-100 rounded-lg md:rounded-xl">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-600">Total Clicks</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $links->sum('clicks') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-2 md:p-3 bg-purple-100 rounded-lg md:rounded-xl">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-600">Active</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $links->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-2 md:p-3 bg-orange-100 rounded-lg md:rounded-xl">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $links->sum('clicks') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Links Table -->
    <div class="bg-white rounded-xl md:rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-900 to-black px-4 md:px-6 py-3 md:py-4">
            <h3 class="text-base md:text-lg font-semibold text-white">Your WhatsApp Links</h3>
        </div>

        <!-- Mobile/Table View -->
        <div class="block sm:hidden">
            <div class="px-4 py-3 space-y-4">
                @forelse($links as $l)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <!-- Link Header -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1 min-w-0 mr-2">
                            <a href="{{ url($l->slug) }}" target="_blank" 
                               class="font-semibold text-gray-900 hover:text-black transition flex items-center truncate">
                                {{ $l->name }}
                                <svg class="w-4 h-4 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <p class="text-xs text-gray-500 mt-1 font-mono truncate">{{ $l->slug }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap flex-shrink-0
                            {{ $l->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $l->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Link Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="truncate">{{ $l->phone }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                            </svg>
                            <span>{{ $l->clicks }} clicks</span>
                        </div>
                    </div>

                    <!-- Message Preview -->
                    @if($l->message)
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 truncate">{{ $l->message }}</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <!-- URL Copy -->
                        <button type="button" onclick="copyToClipboard('{{ url($l->slug) }}')" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition flex-1 min-w-[80px] justify-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copy
                        </button>
                        
                        <!-- QR Code -->
                        <button type="button" onclick="generateQRCode('{{ url($l->slug) }}', '{{ $l->name }}')" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition flex-1 min-w-[80px] justify-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            QR
                        </button>

                        <!-- Analytics -->
                        <a href="{{ route('wa-links.analytics', $l) }}" 
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition flex-1 min-w-[80px] justify-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Stats
                        </a>

                        <!-- Edit -->
                        <a href="{{ route('wa-links.edit', $l) }}" 
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition flex-1 min-w-[80px] justify-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('wa-links.destroy', $l) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this link?');" class="flex-1 min-w-[80px]">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition justify-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <p class="text-lg font-medium text-gray-600">No links created yet</p>
                        <p class="text-sm text-gray-500 mt-1">Get started by creating your first WhatsApp link</p>
                        <a href="{{ route('wa-links.create') }}" 
                           class="inline-flex items-center px-4 py-2 mt-4 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Create Your First Link
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Link Details</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clicks</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 md:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200">
                    @forelse($links as $l)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 md:px-6 py-4">
                            <div>
                                <a href="{{ url($l->slug) }}" target="_blank" 
                                   class="font-semibold text-gray-900 hover:text-black transition flex items-center">
                                    {{ $l->name }}
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <p class="text-sm text-gray-500 mt-1 font-mono truncate max-w-xs">{{ $l->slug }}</p>
                                @if($l->message)
                                    <p class="text-xs text-gray-600 mt-1 truncate max-w-xs">{{ $l->message }}</p>
                                @endif
                                
                                <!-- URL Copy and QR Code Buttons -->
                                <div class="flex space-x-2 mt-2">
                                    <button type="button" onclick="copyToClipboard('{{ url($l->slug) }}')" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Copy URL
                                    </button>
                                    
                                    <button type="button" onclick="generateQRCode('{{ url($l->slug) }}', '{{ $l->name }}')" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                        QR Code
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            <div class="flex items-center text-sm text-gray-900">
                                <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="truncate max-w-[120px]">{{ $l->phone }}</span>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-gray-900">{{ $l->clicks }}</span>
                                <span class="text-xs text-gray-500 ml-1">clicks</span>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $l->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $l->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            <div class="flex justify-end space-x-2">
                                <!-- Analytics Button -->
                                <a href="{{ route('wa-links.analytics', $l) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Stats
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('wa-links.edit', $l) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('wa-links.destroy', $l) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this link?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-600">No links created yet</p>
                                <p class="text-sm text-gray-500 mt-1">Get started by creating your first WhatsApp link</p>
                                <a href="{{ route('wa-links.create') }}" 
                                   class="inline-flex items-center px-4 py-2 mt-4 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                    Create Your First Link
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($links->hasPages())
        <div class="bg-gray-50 px-4 md:px-6 py-4 border-t border-gray-200">
            <div class="flex justify-center">
                {{ $links->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- JavaScript Functions -->
    <script>
    // Define functions in global scope
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('URL copied to clipboard!', 'success');
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            showToast('Failed to copy URL', 'error');
        });
    }

    window.generateQRCode = function(url, title = 'QR Code') {
        // Create modal for QR code
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-xl w-full max-w-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">${title}</h3>
                    <button type="button" onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center mb-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(url)}" 
                         alt="QR Code for ${title}" 
                         class="w-40 h-40 md:w-48 md:h-48 border border-gray-200 rounded-lg"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRkZGRkZGIi8+Cjx0ZXh0IHg9IjEwMCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTk5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSI0Ij5RUiBDb2RlIEVycm9yPC90ZXh0Pgo8L3N2Zz4K'">
                </div>
                <div class="text-center">
                    <p class="text-xs md:text-sm text-gray-600 mb-3">Scan this QR code to open the link</p>
                    <button type="button" onclick="copyToClipboard('${url}')" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition w-full justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy URL
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    window.showToast = function(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.custom-toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `custom-toast fixed top-4 right-4 px-4 md:px-6 py-2 md:py-3 rounded-lg shadow-lg z-50 text-sm ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
    </script>

    <!-- Additional CSS for better mobile experience -->
    <style>
        /* Prevent horizontal scrolling on mobile */
        body {
            overflow-x: hidden;
        }
        
        /* Ensure content doesn't overflow on small screens */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Improve button sizes on mobile */
        @media (max-width: 640px) {
            .mobile-action-buttons button,
            .mobile-action-buttons a {
                padding: 8px 12px;
                font-size: 0.75rem;
            }
            
            .mobile-action-buttons form {
                width: 100%;
            }
            
            .mobile-action-buttons button,
            .mobile-action-buttons a {
                min-height: 36px;
            }
        }
        
        /* Ensure table is responsive on desktop */
        @media (min-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                min-width: 100%;
                table-layout: auto;
            }
            
            table td, table th {
                white-space: nowrap;
            }
            
            /* Allow text truncation in table cells */
            .max-w-xs {
                max-width: 12rem;
            }
        }
    </style>
@endsection