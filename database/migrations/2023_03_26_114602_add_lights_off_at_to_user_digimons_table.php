<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->dateTime('lights_off_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->dropColumn('lights_off_at');
        });
    }
};
