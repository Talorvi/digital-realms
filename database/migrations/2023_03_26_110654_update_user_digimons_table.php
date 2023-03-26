<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->float('hunger')->default(100)->change();
            $table->float('mess')->default(0)->change();
            $table->float('energy')->default(100)->change();
            $table->float('weight')->default(10)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->integer('hunger')->default(100)->change();
            $table->integer('mess')->default(0)->change();
            $table->integer('energy')->default(100)->change();
            $table->integer('weight')->default(10)->change();
        });
    }
};
