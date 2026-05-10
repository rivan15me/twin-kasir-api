<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('point_transactions', function (Blueprint $table) {
        $table->id();
        $table->string('customer_id');
        $table->string('order_id')->default('');
        $table->integer('points');
        $table->string('type'); // 'earn' atau 'redeem'
        $table->string('note')->default('');
        $table->timestamps();

       
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
