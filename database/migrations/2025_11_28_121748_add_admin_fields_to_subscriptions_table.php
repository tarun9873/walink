<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminFieldsToSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('subscriptions', 'assigned_by_admin')) {
                $table->boolean('assigned_by_admin')->default(false);
            }
            
            if (!Schema::hasColumn('subscriptions', 'notes')) {
                $table->text('notes')->nullable();
            }
            
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active');
            }
            
            if (!Schema::hasColumn('subscriptions', 'ends_at')) {
                $table->timestamp('ends_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('subscriptions', 'assigned_by_admin')) {
                $table->dropColumn('assigned_by_admin');
            }
            
            if (Schema::hasColumn('subscriptions', 'notes')) {
                $table->dropColumn('notes');
            }
            
            if (Schema::hasColumn('subscriptions', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('subscriptions', 'ends_at')) {
                $table->dropColumn('ends_at');
            }
        });
    }
}