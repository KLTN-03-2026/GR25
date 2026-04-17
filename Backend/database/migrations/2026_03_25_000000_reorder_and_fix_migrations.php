<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration ensures all independent tables exist before dependent ones.
     */
    public function up(): void
    {
        // Ensure TinhThanh exists (independent)
        if (!Schema::hasTable('tinh_thanhs')) {
            Schema::create('tinh_thanhs', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->timestamps();
            });
        }

        // Ensure LoaiBatDongSan exists (independent)
        if (!Schema::hasTable('loai_bat_dong_sans')) {
            Schema::create('loai_bat_dong_sans', function (Blueprint $table) {
                $table->id();
                $table->string('ten_loai');
                $table->timestamps();
            });
        }

        // Ensure TrangThaiBatDongSan exists (independent)
        if (!Schema::hasTable('trang_thai_bat_dong_sans')) {
            Schema::create('trang_thai_bat_dong_sans', function (Blueprint $table) {
                $table->id();
                $table->string('ten_trang_thai');
                $table->timestamps();
            });
        }

        // Ensure GoiTin exists (independent)
        if (!Schema::hasTable('goi_tins')) {
            Schema::create('goi_tins', function (Blueprint $table) {
                $table->id();
                $table->string('ten_goi');
                $table->decimal('gia', 15, 0);
                $table->integer('so_ngay');
                $table->integer('so_luong_tin');
                $table->timestamps();
            });
        }

        // Ensure QuanHuyen exists (depends on TinhThanh)
        if (!Schema::hasTable('quan_huyens')) {
            Schema::create('quan_huyens', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->foreignId('tinh_id')->constrained('tinh_thanhs', 'id');
                $table->timestamps();
            });
        }

        // Ensure DiaChi exists (depends on TinhThanh & QuanHuyen)
        if (!Schema::hasTable('dia_chis')) {
            Schema::create('dia_chis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tinh_id')->constrained('tinh_thanhs', 'id');
                $table->foreignId('quan_id')->constrained('quan_huyens', 'id');
                $table->string('dia_chi_chi_tiet');
                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();
                $table->timestamps();
            });
        }

        // Ensure KhachHang exists (independent)
        if (!Schema::hasTable('khach_hangs')) {
            Schema::create('khach_hangs', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('email')->unique();
                $table->string('so_dien_thoai');
                $table->string('password');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Ensure MoiGioi exists (independent)
        if (!Schema::hasTable('moi_giois')) {
            Schema::create('moi_giois', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('email')->unique();
                $table->string('so_dien_thoai');
                $table->string('password');
                $table->string('avatar')->nullable();
                $table->text('mo_ta')->nullable();
                $table->string('zalo_link');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We don't drop tables here to preserve existing data
        // These tables should only be dropped manually or via migration:fresh
    }
};
