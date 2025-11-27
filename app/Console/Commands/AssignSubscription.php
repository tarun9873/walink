<?php
// app/Console/Commands/AssignSubscription.php
namespace App\Console\Commands;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Console\Command;

class AssignSubscription extends Command
{
    protected $signature = 'subscription:assign {user} {plan}';
    protected $description = 'Assign a subscription to a user for testing';

    public function handle()
    {
        $user = User::where('email', $this->argument('user'))->first();
        $plan = Plan::where('slug', $this->argument('plan'))->first();

        if (!$user || !$plan) {
            $this->error('User or plan not found!');
            return 1;
        }

        // Deactivate existing subscriptions
        $user->subscriptions()->update(['is_active' => false]);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
            'is_active' => true,
            'status' => 'active',
        ]);

        $this->info("✅ {$plan->name} subscription assigned to {$user->email}");

        return 0;
    }
}