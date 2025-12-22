@extends('layouts.admin')
@php
    use App\Models\CallLink;
@endphp

@section('content')
<div class="px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">User Plan Management</h2>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Assign New Plan Form -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign New Plan to User</h3>
            <form action="{{ route('admin.assign-plan') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- User Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select User *</label>
                        <select name="user_id" id="userSelect" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choose a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                    @if($user->activeSubscription)
                                        - Currently: {{ $user->activeSubscription->plan->name }}
                                    @else
                                        - No active plan
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Plan Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Plan *</label>
                        <select name="plan_id" id="planSelect" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choose a plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} - ₹{{ $plan->price }}/{{ $plan->billing_cycle }} - {{ $plan->links_limit }} links
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

                    <!-- Extra Links -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Extra Links</label>
                        <input type="number" name="extra_links" value="0" min="0"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Additional links beyond plan limit</p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                    <textarea name="notes" rows="3" placeholder="Reason for assignment, special instructions..."
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                        Assign Plan
                    </button>

                </div>
            </form>
        </div>

        <!-- Manage Existing Users -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Manage User Plans</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Links Used/Limit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Extra Links</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                           @php
    $subscription = $user->activeSubscription;

    // 🔢 COMBINED USED LINKS (WA + CALL)
    $linksUsed =
        $user->waLinks()->where('is_active', 1)->count()
      + \App\Models\CallLink::where('user_id', $user->id)->where('is_active', 1)->count();

    $planLimit  = $subscription ? $subscription->plan->links_limit : 0;
    $extraLinks = $subscription ? ($subscription->extra_links ?? 0) : 0;
    $totalLimit = $planLimit + $extraLinks;

    // ⏳ EXPIRY (FIXED COLUMN)
    if ($subscription && $subscription->expires_at) {
        $expiryDate = \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y');
        $isActive   = \Carbon\Carbon::parse($subscription->expires_at)->isFuture();
    } else {
        $expiryDate = 'No plan';
        $isActive   = false;
    }
@endphp

                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $subscription ? $subscription->plan->name : 'No Plan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $linksUsed }} / {{ $totalLimit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $extraLinks }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $expiryDate }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($subscription)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $isActive ? 'Active' : 'Expired' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            No Plan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($subscription)
                                            <!-- Extend Plan -->
                                            <button type="button" 
                                                    data-user-id="{{ $user->id }}" 
                                                    data-user-name="{{ $user->name }}" 
                                                    class="extend-btn text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded text-xs">
                                                <i class="fas fa-calendar-plus mr-1"></i>Extend
                                            </button>
                                            
                                            <!-- Manage Links -->
                                            <button type="button" 
                                                    data-user-id="{{ $user->id }}" 
                                                    data-user-name="{{ $user->name }}"
                                                    data-current-links="{{ $extraLinks }}"
                                                    class="links-btn text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded text-xs">
                                                <i class="fas fa-link mr-1"></i>Manage Links
                                            </button>
                                               <button
  onclick="openTransferModal({{ $user->id }}, '{{ $user->email }}')"
  class="text-orange-600 bg-orange-50 px-3 py-1 rounded text-xs">
  Transfer Links
</button>
                                            <!-- Upgrade Plan -->
                                            <button type="button" 
                                                    data-user-id="{{ $user->id }}" 
                                                    data-user-name="{{ $user->name }}" 
                                                    data-plan-id="{{ $subscription->plan->id }}" 
                                                    class="upgrade-btn text-purple-600 hover:text-purple-900 bg-purple-50 px-3 py-1 rounded text-xs">
                                                <i class="fas fa-level-up-alt mr-1"></i>Upgrade
                                            </button>
                                        @else
                                            <!-- Assign Plan -->
                                            <button type="button" 
                                                    onclick="window.location.href='{{ route('admin.assign-plan', $user->id) }}'" 
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded text-xs">
                                                <i class="fas fa-gift mr-1"></i>Assign Plan
                                            </button>
                                            
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Extend Plan Modal -->
<div id="extendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Extend Plan</h3>
            <form id="extendForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Extend Plan for</label>
                    <p id="extendUserName" class="text-sm text-gray-900 font-semibold"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Extend By (Days)</label>
                    <input type="number" name="extend_days" min="1" value="30" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('extendModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Extend Plan
                    </button>

                 

                </div>
            </form>
        </div>
    </div>
</div>


<div id="transferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
  <div class="bg-white w-96 mx-auto mt-32 p-6 rounded">
    <h3 class="font-bold mb-4">Transfer Links</h3>

    <form id="transferForm" method="POST">
      @csrf

      <p class="text-sm mb-2">
        From: <span id="fromUserEmail" class="font-semibold"></span>
      </p>

      <div class="mb-3">
        <label class="text-sm">Transfer To (Free Trial User)</label>
        <select name="to_user_id" class="w-full border rounded" required>
          @foreach($users as $u)
            <option value="{{ $u->id }}">{{ $u->email }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="text-sm">Number of Links</label>
        <input type="number" name="links_count" min="1" required
               class="w-full border rounded">
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeTransferModal()"
                class="px-3 py-1 bg-gray-300 rounded">Cancel</button>

        <button class="px-3 py-1 bg-orange-600 text-white rounded">
          Transfer
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Manage Links Modal -->
<div id="linksModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Manage Extra Links</h3>
            <form id="linksForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Manage Links for</label>
                    <p id="linksUserName" class="text-sm text-gray-900 font-semibold"></p>
                    <p id="currentLinksInfo" class="text-xs text-gray-500 mt-1"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Set Extra Links</label>
                    <input type="number" name="additional_links" min="0" value="0" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">
                        Set new total extra links. Current links will be replaced with this value.
                    </p>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center text-xs text-gray-600">
                            <span class="w-24">Quick Set:</span>
                            <button type="button" onclick="setLinksValue(0)" class="text-red-600 hover:text-red-800 mx-1">0</button>
                            <button type="button" onclick="setLinksValue(10)" class="text-blue-600 hover:text-blue-800 mx-1">10</button>
                            <button type="button" onclick="setLinksValue(50)" class="text-blue-600 hover:text-blue-800 mx-1">50</button>
                            <button type="button" onclick="setLinksValue(100)" class="text-blue-600 hover:text-blue-800 mx-1">100</button>
                            <button type="button" onclick="setLinksValue(500)" class="text-green-600 hover:text-green-800 mx-1">500</button>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('linksModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Update Links
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upgrade Plan Modal -->
<div id="upgradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Upgrade Plan</h3>
            <form id="upgradeForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upgrade Plan for</label>
                    <p id="upgradeUserName" class="text-sm text-gray-900 font-semibold"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select New Plan</label>
                    <select name="new_plan_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">
                                {{ $plan->name }} - ₹{{ $plan->price }} - {{ $plan->links_limit }} links
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('upgradeModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Upgrade Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Quick actions for links
function setLinksValue(value) {
    const input = document.querySelector('#linksForm input[name="additional_links"]');
    input.value = value;
}

// Modal Functions
function openExtendModal(userId, userName) {
    document.getElementById('extendUserName').textContent = userName;
    document.getElementById('extendForm').action = `/admin/users/${userId}/extend-plan`;
    document.getElementById('extendModal').classList.remove('hidden');
}

function openLinksModal(userId, userName, currentLinks) {
    document.getElementById('linksUserName').textContent = userName;
    document.getElementById('currentLinksInfo').textContent = `Current extra links: ${currentLinks}`;
    
    // Set current value in input
    const input = document.querySelector('#linksForm input[name="additional_links"]');
    input.value = currentLinks;
    
    document.getElementById('linksForm').action = `/admin/users/${userId}/add-links`;
    document.getElementById('linksModal').classList.remove('hidden');
}

function openUpgradeModal(userId, userName, currentPlanId) {
    document.getElementById('upgradeUserName').textContent = userName;
    document.getElementById('upgradeForm').action = `/admin/users/${userId}/upgrade-plan`;
    
    const planSelect = document.querySelector('#upgradeForm select[name="new_plan_id"]');
    if (planSelect && currentPlanId) {
        planSelect.value = currentPlanId;
    }
    
    document.getElementById('upgradeModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, attaching event listeners...');
    
    // Extend buttons
    document.querySelectorAll('.extend-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            openExtendModal(userId, userName);
        });
    });
    
    // Links buttons
    document.querySelectorAll('.links-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const currentLinks = this.getAttribute('data-current-links') || 0;
            openLinksModal(userId, userName, currentLinks);
        });
    });
    
    // Upgrade buttons
    document.querySelectorAll('.upgrade-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const planId = this.getAttribute('data-plan-id');
            openUpgradeModal(userId, userName, planId);
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const modals = ['extendModal', 'linksModal', 'upgradeModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('extendModal');
            closeModal('linksModal');
            closeModal('upgradeModal');
        }
    });
});

// Form submission handlers
document.getElementById('linksForm')?.addEventListener('submit', function(e) {
    console.log('Update Links form submitted');
});

document.getElementById('extendForm')?.addEventListener('submit', function(e) {
    console.log('Extend form submitted');
});

document.getElementById('upgradeForm')?.addEventListener('submit', function(e) {
    console.log('Upgrade form submitted');
});

function openTransferModal(userId, email) {
    document.getElementById('fromUserEmail').innerText = email;
    document.getElementById('transferForm').action =
        `/admin/users/${userId}/transfer-links`;
    document.getElementById('transferModal').classList.remove('hidden');
}

function closeTransferModal() {
    document.getElementById('transferModal').classList.add('hidden');
}
</script>

@endsection