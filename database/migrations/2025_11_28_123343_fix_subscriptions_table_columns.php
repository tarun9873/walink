<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSubscriptionsTableColumns extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Add expires_at if it doesn't exist
            if (!Schema::hasColumn('subscriptions', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('plan_id');
            }
            
            // Add ends_at if it doesn't exist  
            if (!Schema::hasColumn('subscriptions', 'ends_at')) {
                $table->timestamp('ends_at')->nullable()->after('expires_at');
            }
            
            // Add status if it doesn't exist
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active')->after('ends_at');
            }
            
            // Add assigned_by_admin if it doesn't exist
            if (!Schema::hasColumn('subscriptions', 'assigned_by_admin')) {
                $table->boolean('assigned_by_admin')->default(false)->after('status');
            }
            
            // Add notes if it doesn't exist
            if (!Schema::hasColumn('subscriptions', 'notes')) {
                $table->text('notes')->nullable()->after('assigned_by_admin');
            }
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Optional rollback
        });
    }
}