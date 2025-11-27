<?php
// app/Http/Controllers/SubscriptionController.php
namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    public function subscribe(Plan $plan)
    {
        $user = auth()->user();
        
        // Check if user already has active subscription
        if ($user->hasActiveSubscription()) {
            return redirect()->route('wa-links.index')
                ->with('info', 'You already have an active subscription.');
        }

        // In real implementation, this would redirect to Stripe/PayPal
        // For now, we'll simulate successful subscription
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
            'is_active' => true,
            'status' => 'active',
            // In real implementation, you'd get these from Stripe
            'stripe_subscription_id' => 'simulated_' . uniqid(),
            'stripe_price_id' => 'simulated_price_' . $plan->id,
        ]);

        return redirect()->route('subscription.success')
            ->with('success', 'Subscription activated successfully!');
    }

    public function success()
    {
        if (!session('success')) {
            return redirect()->route('pricing');
        }
        
        return view('subscription.success');
    }

    public function cancel()
    {
        return view('subscription.cancel');
    }
}