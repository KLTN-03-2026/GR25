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
        if (!Schema::hasTable('giao_dichs')) {
            Schema::create('giao_dichs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('moi_gioi_id')->constrained('moi_giois', 'id');   // ai mua
                $table->foreignId('goi_tin_id')->constrained('goi_tins', 'id');    // gói nào

                $table->decimal('so_tien', 15, 0);

                $table->string('phuong_thuc'); // momo, bank, cash

                $table->string('trang_thai'); // pending, success, fail

                $table->string('ma_giao_dich')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giao_dichs');
    }
};
