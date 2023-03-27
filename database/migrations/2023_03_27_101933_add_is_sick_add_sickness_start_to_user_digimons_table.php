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
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->boolean('is_sick')->default(false);
            $table->timestamp('sicknes_start')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->dropColumn('is_sick');
            $table->dropColumn('sickness_start');
        });
    }
};
