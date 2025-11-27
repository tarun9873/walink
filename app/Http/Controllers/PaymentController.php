<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showForm(Plan $plan)
    {
        return view('payments.form', compact('plan'));
    }

    public function processPayment(Request $request, Plan $plan)
    {
        try {
            $user = Auth::user();
            
            // Validate form data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:20',
                'upi_id' => 'required|string|max:255',
                'transaction_note' => 'nullable|string|max:500'
            ]);

            // Here you would typically:
            // 1. Save customer payment details
            // 2. Process payment with payment gateway
            // 3. Create subscription

            // For now, we'll create subscription directly
            Subscription::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
                'is_active' => true,
                'status' => 'active',
                'payment_data' => json_encode($validated) // Store payment details
            ]);

            return redirect()->route('dashboard')->with('success', 
                'Payment successful! Your ' . $plan->name . ' plan has been activated.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 
                'Payment processing failed: ' . $e->getMessage());
        }
    }
}