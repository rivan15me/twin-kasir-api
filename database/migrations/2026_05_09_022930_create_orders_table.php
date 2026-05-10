<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('customer_id')->nullable();
            $table->string('admin_id')->nullable();
            $table->string('admin_name')->default('');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('remaining', 12, 2)->default(0);
            $table->string('payment_type')->default('lunas');
            $table->string('payment_status')->default('lunas');
            $table->string('due_date')->nullable();
            $table->text('notes')->default('');
            $table->string('payment_method');
            $table->string('payment_reference')->default('');
            $table->string('payment_id')->default('');
            $table->string('service_date')->nullable();
            $table->integer('rental_days')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};