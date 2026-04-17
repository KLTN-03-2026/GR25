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
        if (!Schema::hasTable('lich_su_goi_tins')) {
            Schema::create('lich_su_goi_tins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('moi_gioi_id')->constrained('moi_giois', 'id');
                $table->foreignId('goi_tin_id')->constrained('goi_tins', 'id');

                $table->date('ngay_bat_dau');
                $table->date('ngay_ket_thuc');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_goi_tins');
    }
};
