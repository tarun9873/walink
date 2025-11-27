<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wa_links', function (Blueprint $table) {
            // add 'name' column nullable so existing rows won't break
            $table->string('name', 100)->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('wa_links', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};