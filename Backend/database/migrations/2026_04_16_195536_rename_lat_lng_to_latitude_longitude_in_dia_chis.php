<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dia_chis', function (Blueprint $table) {
            $table->renameColumn('lat', 'latitude');
            $table->renameColumn('lng', 'longitude');
        });
    }

    public function down()
    {
        Schema::table('dia_chis', function (Blueprint $table) {
            $table->renameColumn('latitude', 'lat');
            $table->renameColumn('longitude', 'lng');
        });
    }
};
