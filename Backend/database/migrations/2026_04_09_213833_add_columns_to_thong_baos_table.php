<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('thong_baos', function (Blueprint $table) {
            // ✅ Chỉ thêm cột nếu chưa tồn tại
            if (!Schema::hasColumn('thong_baos', 'moi_gioi_id')) {
                $table->foreignId('moi_gioi_id')->nullable()->after('id')->constrained('moi_giois')->onDelete('cascade');
            }

            if (!Schema::hasColumn('thong_baos', 'khach_hang_id')) {
                $table->foreignId('khach_hang_id')->nullable()->after('moi_gioi_id')->constrained('khach_hangs')->onDelete('set null');
            }

            if (!Schema::hasColumn('thong_baos', 'bat_dong_san_id')) {
                $table->foreignId('bat_dong_san_id')->nullable()->after('khach_hang_id')->constrained('bat_dong_sans')->onDelete('cascade');
            }

            if (!Schema::hasColumn('thong_baos', 'tieu_de')) {
                $table->string('tieu_de')->nullable()->after('bat_dong_san_id');
            }

            if (!Schema::hasColumn('thong_baos', 'noi_dung')) {
                $table->text('noi_dung')->nullable()->after('tieu_de');
            }

            if (!Schema::hasColumn('thong_baos', 'trang_thai')) {
                $table->tinyInteger('trang_thai')->default(0)->after('noi_dung');
            }

            if (!Schema::hasIndex('thong_baos', ['moi_gioi_id', 'trang_thai'])) {
                $table->index(['moi_gioi_id', 'trang_thai']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('thong_baos', function (Blueprint $table) {
            if (Schema::hasIndex('thong_baos', ['moi_gioi_id', 'trang_thai'])) {
                $table->dropIndex(['moi_gioi_id', 'trang_thai']);
            }

            if (Schema::hasColumn('thong_baos', 'moi_gioi_id')) {
                $table->dropForeign(['moi_gioi_id']);
                $table->dropColumn('moi_gioi_id');
            }

            if (Schema::hasColumn('thong_baos', 'khach_hang_id')) {
                $table->dropForeign(['khach_hang_id']);
                $table->dropColumn('khach_hang_id');
            }

            if (Schema::hasColumn('thong_baos', 'bat_dong_san_id')) {
                $table->dropForeign(['bat_dong_san_id']);
                $table->dropColumn('bat_dong_san_id');
            }

            if (Schema::hasColumn('thong_baos', 'tieu_de')) {
                $table->dropColumn('tieu_de');
            }

            if (Schema::hasColumn('thong_baos', 'noi_dung')) {
                $table->dropColumn('noi_dung');
            }

            if (Schema::hasColumn('thong_baos', 'trang_thai')) {
                $table->dropColumn('trang_thai');
            }
        });
    }
};
