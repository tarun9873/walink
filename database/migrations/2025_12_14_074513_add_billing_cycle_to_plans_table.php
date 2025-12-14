<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingCycleToPlansTable extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->enum('billing_cycle', ['month', 'year', 'custom'])
                  ->default('month')
                  ->after('duration_days');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
        });
    }
}