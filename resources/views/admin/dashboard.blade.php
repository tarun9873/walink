@extends('layouts.admin')

@section('content')
<div class="px-4 py-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Subscriptions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_subscriptions'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Plans</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_plans'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-rupee-sign text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today's Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">₹{{ $stats['revenue_today'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Subscriptions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Subscriptions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentSubscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $subscription->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $subscription->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subscription->plan->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($subscription->starts_at)
                                {{ $subscription->starts_at->format('d M Y') }}
                            @else
                                <span class="text-gray-400">Not set</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($subscription->ends_at)
                                {{ $subscription->ends_at->format('d M Y') }}
                            @else
                                <span class="text-yellow-600">No expiry</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subscription->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($subscription->status === 'cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.assign-plan.form') }}" class="bg-white shadow rounded-lg p-6 text-center hover:shadow-md transition-shadow">
            <i class="fas fa-gift text-3xl text-blue-600 mb-3"></i>
            <h4 class="text-lg font-medium text-gray-900">Assign Plan</h4>
            <p class="text-gray-500 mt-2">Assign subscription plans to users</p>
        </a>

        <a href="{{ route('admin.users') }}" class="bg-white shadow rounded-lg p-6 text-center hover:shadow-md transition-shadow">
            <i class="fas fa-users text-3xl text-green-600 mb-3"></i>
            <h4 class="text-lg font-medium text-gray-900">Manage Users</h4>
            <p class="text-gray-500 mt-2">View and manage all users</p>
        </a>

        <a href="{{ route('admin.plans') }}" class="bg-white shadow rounded-lg p-6 text-center hover:shadow-md transition-shadow">
            <i class="fas fa-box text-3xl text-purple-600 mb-3"></i>
            <h4 class="text-lg font-medium text-gray-900">Manage Plans</h4>
            <p class="text-gray-500 mt-2">Create and manage subscription plans</p>
        </a>
    </div>
</div>

<script>
// Auto-hide notifications after 5 seconds
setTimeout(() => {
    const notifications = document.querySelectorAll('.bg-green-100, .bg-red-100');
    notifications.forEach(notification => {
        if (notification.closest('.relative') || notification.closest('.bg-green-100, .bg-red-100')) {
            notification.style.display = 'none';
        }
    });
}, 5000);
</script>
@endsection