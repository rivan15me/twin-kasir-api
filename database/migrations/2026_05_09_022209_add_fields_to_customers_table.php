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
    Schema::table('customers', function (Blueprint $table) {
        $table->string('email')->default('')->after('name');
        $table->string('phone2')->default('')->after('phone');
        $table->string('address')->default('')->after('phone2');
        $table->integer('total_points')->default(0)->after('address');
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn(['email', 'phone2', 'address', 'total_points']);
    });
}
};
