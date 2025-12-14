<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subscription Successful
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome to Pro!</h1>
                <p class="text-gray-600 mb-6">Your subscription has been activated successfully.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('wa-links.create') }}" 
                       class="inline-block bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Create Your First Link
                    </a>
                    <br>
                    <a href="{{ route('wa-links.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>