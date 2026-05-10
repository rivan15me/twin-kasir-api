<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin')->after('password');
            }
            if (!Schema::hasColumn('users', 'photo_path')) {
                $table->string('photo_path')->default('')->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};
