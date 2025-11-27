<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wa_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wa_link_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('referrer')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->timestamps();

            // Index for better performance
            $table->index(['wa_link_id', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wa_link_clicks');
    }
};