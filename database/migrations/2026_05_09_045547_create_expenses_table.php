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
    Schema::create('expenses', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->date('tanggal');
        $table->string('kategori')->default('Lain-lain');
        $table->string('keterangan');
        $table->decimal('jumlah', 12, 2);
        $table->string('image_path')->default('');
        $table->string('created_by')->default('');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
