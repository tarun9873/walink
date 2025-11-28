@extends('layouts.admin')

@section('content')
<div class="px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Assign Plan to User</h2>

        <!-- Assign Plan Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.assign-plan') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- User Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select User *</label>
                        <select name="user_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choose a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ $selectedUser && $selectedUser->id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                    @if($user->hasActiveSubscription())
                                        - Currently on {{ $user->activeSubscription->plan->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Plan Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Plan *</label>
                        <select name="plan_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choose a plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} - ₹{{ $plan->price }}/{{ $plan->billing_cycle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Days) *</label>
                        <input type="number" name="duration_days" value="30" min="1" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <input type="text" name="notes" placeholder="Reason for assignment..."
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg transform transition-all duration-200 hover:scale-105">
                        <i class="fas fa-gift mr-2"></i>Assign Plan
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Active Subscriptions -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recently Assigned Plans</h3>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires On</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $recentSubscriptions = \App\Models\Subscription::with(['user', 'plan'])
                                ->where('assigned_by_admin', true)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        
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
                                {{ $subscription->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subscription->ends_at->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection