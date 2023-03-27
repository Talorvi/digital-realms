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
        Schema::table('user_digimon_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('age')->default(0);
            $table->unsignedBigInteger('deaths')->default(0);
            $table->unsignedBigInteger('feeds')->default(0);
            $table->unsignedBigInteger('illnesses')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('user_digimon_stats', function (Blueprint $table) {
            $table->dropColumn(['age', 'deaths', 'feeds', 'illnesses']);
        });
    }
};
