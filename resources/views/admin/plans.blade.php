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

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    </div>
    @endif

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
                    <div class="text-right">
                        <span class="text-2xl font-bold text-gray-900">₹{{ number_format($plan->price, 0) }}</span>
                        <div class="text-sm text-gray-500">
                            / 
                            @if($plan->billing_cycle == 'year')
                                year
                            @elseif($plan->billing_cycle == 'month')
                                month
                            @else
                                {{ $plan->duration_days }} days
                            @endif
                        </div>
                        @if($plan->billing_cycle == 'year')
                        <div class="text-xs text-green-600 mt-1">
                            ₹{{ number_format($plan->price / 12, 0) }}/month
                        </div>
                        @endif
                    </div>
                </div>
                
                <p class="text-gray-600 mb-4">{{ $plan->description ?? 'No description' }}</p>
                
                <div class="mb-4">
                    <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">
                        @if($plan->billing_cycle == 'year')
                            Yearly • 
                        @elseif($plan->billing_cycle == 'month')
                            Monthly • 
                        @else
                            {{ $plan->duration_days }} Days • 
                        @endif
                        {{ $plan->links_limit }} Links
                    </span>
                    <span class="inline-block ml-2 {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded text-sm">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($plan->is_popular)
                    <span class="inline-block ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                        <i class="fas fa-crown mr-1"></i>Popular
                    </span>
                    @endif
                    @if($plan->sort_order > 0)
                    <span class="inline-block ml-2 bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                        Order: {{ $plan->sort_order }}
                    </span>
                    @endif
                </div>

                <ul class="space-y-2 mb-6">
                    @php
                        $features = is_array($plan->features) ? $plan->features : json_decode($plan->features, true);
                        $features = $features ?? [];
                    @endphp
                    @if(count($features) > 0)
                        @foreach($features as $feature)
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            {{ $feature }}
                        </li>
                        @endforeach
                    @else
                        <li class="text-sm text-gray-500 italic">No features specified</li>
                    @endif
                </ul>

                <div class="flex space-x-2 mt-4">
                    <form action="{{ route('admin.toggle-plan', $plan->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $plan->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 px-4 rounded text-sm font-medium">
                            {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <button type="button" 
                            onclick="openDeleteModal('{{ $plan->id }}', '{{ $plan->name }}')"
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Create Plan Modal -->
    <div id="createPlanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Create New Plan</h3>
                <button onclick="document.getElementById('createPlanModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.create-plan') }}" method="POST" id="planForm">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plan Name *</label>
                            <input type="text" name="name" required 
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Basic, Pro, Enterprise"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price (₹) *</label>
                            <input type="number" name="price" step="0.01" required 
                                   value="{{ old('price') }}"
                                   min="0" 
                                   placeholder="e.g., 299, 999, 45999"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" 
                                  placeholder="Short description of the plan"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Links Limit *</label>
                            <input type="number" name="links_limit" required 
                                   value="{{ old('links_limit', 10) }}"
                                   min="1" 
                                   placeholder="e.g., 5, 10, 100"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (Days) *</label>
                            <input type="number" name="duration_days" required 
                                   value="{{ old('duration_days', 30) }}"
                                   min="1" 
                                   placeholder="30 for monthly, 365 for yearly"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                   id="durationDaysInput">
                            <p class="text-xs text-gray-500 mt-1">
                                Monthly: 30, Yearly: 365
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Billing Cycle *</label>
                            <select name="billing_cycle" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    id="billingCycleSelect">
                                <option value="month" {{ old('billing_cycle', 'month') == 'month' ? 'selected' : '' }}>Monthly</option>
                                <option value="year" {{ old('billing_cycle') == 'year' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ old('billing_cycle') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" name="sort_order" 
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   placeholder="0 for first"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <p class="text-xs text-gray-500 mt-1">Lower = first</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Features (One per line) *</label>
                        <textarea name="features[]" required rows="4" 
                                  placeholder="5 WhatsApp Links&#10;Basic Analytics&#10;Email Support&#10;24/7 Customer Support"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('features.0') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Enter each feature on a new line</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   value="1"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active Plan</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_popular" id="is_popular" 
                                   {{ old('is_popular') ? 'checked' : '' }}
                                   value="1"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <label for="is_popular" class="ml-2 text-sm text-gray-700">Mark as Popular Plan</label>
                        </div>
                    </div>
                </div>
                
                @if ($errors->any())
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <h4 class="text-sm font-medium text-red-800">Please fix the following errors:</h4>
                    <ul class="mt-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                        <li class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('createPlanModal').classList.add('hidden')"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                        Create Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Plan Modal -->
    <div id="deletePlanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Plan</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete plan "<span id="planNameToDelete" class="font-semibold"></span>"?
                    <br>
                    <span class="text-red-600 font-medium">This action cannot be undone.</span>
                </p>
                
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-150">
                        Cancel
                    </button>
                    <form id="deletePlanForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                            Delete Plan
                        </button>
                    </form>
                </div>
            </div>
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

    document.getElementById('deletePlanModal').addEventListener('click', function(e) {
        if (e.target.id === 'deletePlanModal') {
            closeDeleteModal();
        }
    });

    // Auto-select billing cycle based on duration
    const durationInput = document.getElementById('durationDaysInput');
    const billingCycleSelect = document.getElementById('billingCycleSelect');
    
    if (durationInput && billingCycleSelect) {
        // Function to update billing cycle
        function updateBillingCycle() {
            const duration = parseInt(durationInput.value);
            
            if (duration >= 365) {
                billingCycleSelect.value = 'year';
            } else if (duration >= 30) {
                billingCycleSelect.value = 'month';
            } else {
                billingCycleSelect.value = 'custom';
            }
        }
        
        // Listen for changes
        durationInput.addEventListener('input', updateBillingCycle);
        durationInput.addEventListener('change', updateBillingCycle);
        
        // Also update on page load if there's a value
        if (durationInput.value) {
            updateBillingCycle();
        }
    }

    // Form validation and debugs
    document.getElementById('planForm').addEventListener('submit', function(e) {
        console.log('Form submitted');
        
        // Collect form data
        const formData = new FormData(this);
        console.log('Form Data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Validate features field
        const featuresTextarea = document.querySelector('textarea[name="features[]"]');
        if (!featuresTextarea.value.trim()) {
            e.preventDefault();
            alert('Please enter at least one feature');
            featuresTextarea.focus();
            return false;
        }
    });

    // Delete Plan Functions
    let planIdToDelete = null;
    
    function openDeleteModal(planId, planName) {
        planIdToDelete = planId;
        document.getElementById('planNameToDelete').textContent = planName;
        
        // Use the correct route
        document.getElementById('deletePlanForm').action = `/admin/plans/${planId}/delete`;
        
        document.getElementById('deletePlanModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        planIdToDelete = null;
        document.getElementById('deletePlanModal').classList.add('hidden');
    }

    // Handle delete form submission
    document.getElementById('deletePlanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!planIdToDelete) {
            alert('No plan selected for deletion');
            return;
        }
        
        // Show confirmation
        if (!confirm('Are you absolutely sure? This will permanently delete the plan.')) {
            return;
        }
        
        // Submit the forms
        this.submit();
    });
</script>
@endsection