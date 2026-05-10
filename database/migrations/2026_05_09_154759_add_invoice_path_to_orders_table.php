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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('invoice_path')->default('')->after('rental_days');
        $table->string('payment_proof')->default('')->after('invoice_path');
    });
    }
    public function down(): void
    {
     Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['invoice_path', 'payment_proof']);
    });
    }
};
