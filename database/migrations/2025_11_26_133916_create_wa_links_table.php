<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
public function up()
{
Schema::create('wa_links', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('phone', 32);
$table->text('message')->nullable();
$table->string('slug')->unique();
$table->text('full_url');
$table->unsignedBigInteger('clicks')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('wa_links');
}
};