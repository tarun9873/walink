<?php
// database/seeders/PlanSeeder.php
namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 299,
                'links_limit' => 5,
                'duration_days' => 30,
                'description' => 'Perfect for individuals getting started',
                'features' => ['5 WhatsApp Links', 'Basic Analytics', 'Email Support'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional', 
                'price' => 799,
                'links_limit' => 25,
                'duration_days' => 30,
                'description' => 'Great for small businesses and professionals',
                'features' => ['25 WhatsApp Links', 'Advanced Analytics', 'Priority Support'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 1999,
                'links_limit' => 100,
                'duration_days' => 30,
                'description' => 'For large businesses with high volume needs',
                'features' => ['100 WhatsApp Links', 'Full Analytics Suite', '24/7 Phone Support'],
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $planData) {
            // Check if plan already exists, if not create it
            Plan::firstOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
        
        $this->command->info('Plans seeded successfully!');
    }
}