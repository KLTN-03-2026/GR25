<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goi_tins', function (Blueprint $table) {
            $table->text('mo_ta')->nullable()->after('ten_goi');
            $table->string('trang_thai')->default('active')->after('so_luong_tin');
        });
    }

    public function down(): void
    {
        Schema::table('goi_tins', function (Blueprint $table) {
            $table->dropColumn(['mo_ta', 'trang_thai']);
        });
    }
};
