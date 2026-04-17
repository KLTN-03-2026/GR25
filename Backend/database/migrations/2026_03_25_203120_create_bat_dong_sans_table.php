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
        if (!Schema::hasTable('bat_dong_sans')) {
            Schema::create('bat_dong_sans', function (Blueprint $table) {
                $table->id();
                $table->string('tieu_de');
                $table->text('mo_ta')->nullable();
                $table->decimal('gia', 15, 0);
                $table->float('dien_tich');
                $table->foreignId('loai_id')->constrained('loai_bat_dong_sans', 'id');
                $table->foreignId('trang_thai_id')->constrained('trang_thai_bat_dong_sans', 'id');
                $table->foreignId('moi_gioi_id')->constrained('moi_giois', 'id');
                $table->foreignId('tinh_id')->constrained('tinh_thanhs', 'id');
                $table->foreignId('quan_id')->constrained('quan_huyens', 'id');
                $table->foreignId('dia_chi_id')->constrained('dia_chis', 'id');
                $table->integer('so_phong_ngu')->nullable();
                $table->integer('so_phong_tam')->nullable();
                $table->boolean('is_duyet')->default(false);
                $table->boolean('is_noi_bat')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bat_dong_sans');
    }
};
