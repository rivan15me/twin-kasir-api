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
        if (!Schema::hasColumn('orders', 'payment_proof')) {
            $table->string('payment_proof')->default('')->after('payment_id');
        }
        if (!Schema::hasColumn('orders', 'invoice_path')) {
            $table->string('invoice_path')->default('')->after('payment_proof');
        }
    });
    }

    public function down(): void
    {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['payment_proof', 'invoice_path']);
    });
    }
};
