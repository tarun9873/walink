@extends('layouts.admin')

@section('content')
<div class="px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Users Management</h2>
        <a href="{{ route('admin.assign-plan.form') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-gift mr-2"></i>Assign Plan
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-400">Joined: {{ $user->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->hasActiveSubscription())
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $user->activeSubscription->plan->name }}
                                </span>
                                
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No Plan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->hasActiveSubscription())
                                <span class="text-green-600 font-medium">Active</span>
                            @else
                                <span class="text-red-600 font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                           @if($user->hasActiveSubscription() && $user->activeSubscription->expires_at)
    {{ \Carbon\Carbon::parse($user->activeSubscription->expires_at)->format('d M Y') }}
    <div class="text-xs text-gray-400">
        {{ \Carbon\Carbon::parse($user->activeSubscription->expires_at)->diffForHumans() }}
    </div>

                            @elseif($user->hasActiveSubscription())
                                <span class="text-yellow-600">No expiry set</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.assign-plan.form', $user->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Assign Plan">
                                    <i class="fas fa-gift"></i>
                                </a>

                                  <!-- 🔥 LOGIN AS USER -->
    <form action="{{ route('admin.impersonate.leave') }}" method="POST">
    @csrf
    <button class="ml-3 text-blue-600 font-semibold">
        Return to Admin
    </button>
</form>

                                
                                @if($user->hasActiveSubscription())
                                <form action="{{ route('admin.extend-plan', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="number" name="extension_days" 
                                           value="{{ $user->activeSubscription->plan->billing_cycle == 'year' ? '365' : '30' }}" 
                                           min="1" 
                                           class="w-16 border rounded px-2 py-1 text-xs" 
                                           placeholder="Days"
                                           title="Extend by days">
                                    <button type="submit" class="text-green-600 hover:text-green-900 ml-1" title="Extend Plan">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.cancel-subscription', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to cancel this subscription?')"
                                            title="Cancel Subscription">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
// Auto-hide notifications after 5 seconds
setTimeout(() => {
    const notifications = document.querySelectorAll('.bg-green-200, .bg-red-200');
    notifications.forEach(notification => {
        if (notification.closest('.relative') || notification.closest('.bg-green-100, .bg-red-100')) {
            notification.style.display = 'none';
        }
    });
}, 5000);

// Dynamic extension days based on plan type
document.addEventListener('DOMContentLoaded', function() {
    const extensionInputs = document.querySelectorAll('input[name="extension_days"]');
    
    extensionInputs.forEach(input => {
        // Get the plan type from the table row
        const planCell = input.closest('tr').querySelector('.bg-blue-100');
        if (planCell) {
            const planText = planCell.textContent.toLowerCase();
            
            // Set default extension days based on plan type
            if (planText.includes('enterprise') || planText.includes('year')) {
                input.value = '365';
            } else if (planText.includes('professional')) {
                input.value = '30';
            } else {
                input.value = '30'; // Default for basic plans
            }
        }
    });
});
</script>
@endsection