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
        if (!Schema::hasTable('dia_chis')) {
            Schema::create('dia_chis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tinh_id')->constrained('tinh_thanhs', 'id');
                $table->foreignId('quan_id')->constrained('quan_huyens', 'id');

                $table->string('dia_chi_chi_tiet'); // số nhà, đường

                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dia_chis');
    }
};
