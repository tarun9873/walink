@extends('layouts.admin')

@section('content')
<div class="px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Plans Management</h2>
        <button onclick="document.getElementById('createPlanModal').classList.remove('hidden')"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-plus mr-2"></i>Create New Plan
        </button>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="bg-white rounded-lg shadow border {{ $plan->is_popular ? 'border-2 border-yellow-400' : 'border-gray-200' }}">
            @if($plan->is_popular)
            <div class="bg-yellow-400 text-white text-center py-1 text-sm font-bold">
                <i class="fas fa-crown mr-1"></i>POPULAR
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    <span class="text-2xl font-bold text-gray-900">₹{{ $plan->price }}</span>
                </div>
                
                <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                
                <div class="mb-4">
                    <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">
                        {{ $plan->billing_cycle }}ly • {{ $plan->links_limit }} Links
                    </span>
                    <span class="inline-block ml-2 {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded text-sm">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <ul class="space-y-2 mb-6">
                    @php
                        $features = is_array($plan->features) ? $plan->features : json_decode($plan->features, true);
                    @endphp
                    @foreach($features as $feature)
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                <div class="flex space-x-2">
                    <form action="{{ route('admin.toggle-plan', $plan->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $plan->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 px-4 rounded text-sm font-medium">
                            {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Create Plan Modal -->
    <div id="createPlanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Create New Plan</h3>
                <button onclick="document.getElementById('createPlanModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.create-plan') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plan Name *</label>
                            <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price (₹) *</label>
                            <input type="number" name="price" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Billing Cycle *</label>
                            <select name="billing_cycle" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Links Limit *</label>
                            <input type="number" name="links_limit" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Features (One per line) *</label>
                        <textarea name="features[]" required rows="4" 
                                  placeholder="5 WhatsApp Links&#10;Basic Analytics&#10;Email Support"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Enter each feature on a new line</p>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_popular" id="is_popular" class="rounded border-gray-300 text-blue-600 shadow-sm">
                        <label for="is_popular" class="ml-2 text-sm text-gray-700">Mark as Popular Plan</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('createPlanModal').classList.add('hidden')"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Close modal when clicking outside
    document.getElementById('createPlanModal').addEventListener('click', function(e) {
        if (e.target.id === 'createPlanModal') {
            this.classList.add('hidden');
        }
    });
</script>
@endsection