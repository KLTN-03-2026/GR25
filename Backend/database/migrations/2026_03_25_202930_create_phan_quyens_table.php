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
        if (!Schema::hasTable('phan_quyens')) {
            Schema::create('phan_quyens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_chuc_vu')->constrained('chuc_vus', 'id')->onDelete('cascade');
                $table->foreignId('id_chuc_nang')->constrained('chuc_nangs', 'id')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_quyens');
    }
};
