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
        if (!Schema::hasTable('yeu_thichs')) {
            Schema::create('yeu_thichs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('moi_gioi_id')->constrained('moi_giois', 'id');     // người nhận
                $table->foreignId('khach_hang_id')->constrained('khach_hangs', 'id');   // người thực hiện hành động

                $table->foreignId('bds_id')->nullable()->constrained('bat_dong_sans', 'id');

                $table->string('noi_dung');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yeu_thichs');
    }
};
