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
        if (!Schema::hasColumn('customers', 'foto1_path')) {
            $table->string('foto1_path')->default('')->after('address');
        }
        if (!Schema::hasColumn('customers', 'foto2_path')) {
            $table->string('foto2_path')->default('')->after('foto1_path');
        }
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn(['foto1_path', 'foto2_path']);
    });
}
};
