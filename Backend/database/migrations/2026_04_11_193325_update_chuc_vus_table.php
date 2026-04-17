<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chuc_vus', function (Blueprint $table) {
            // Xóa cột slug_chuc_vu (nếu tồn tại)
            if (Schema::hasColumn('chuc_vus', 'slug_chuc_vu')) {
                $table->dropColumn('slug_chuc_vu');
            }

            // Thêm cột tình_trạng (dùng tên không dấu để tránh lỗi)
            if (!Schema::hasColumn('chuc_vus', 'tinh_trang')) {
                $table->tinyInteger('tinh_trang')->default(1)->comment('1: Hoạt động, 0: Ngừng hoạt động');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chuc_vus', function (Blueprint $table) {
            // Rollback: thêm lại slug, xóa tinh_trang
            if (!Schema::hasColumn('chuc_vus', 'slug_chuc_vu')) {
                $table->string('slug_chuc_vu')->nullable();
            }
            if (Schema::hasColumn('chuc_vus', 'tinh_trang')) {
                $table->dropColumn('tinh_trang');
            }
        });
    }
};
