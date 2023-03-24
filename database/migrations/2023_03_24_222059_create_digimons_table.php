<?php

use Carbon\Carbon;
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
        Schema::create('digimons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('MissingNo');
            $table->integer('stage')->default(0);
            $table->integer('base_power')->default(0);
            $table->string('type')->default('free');
            $table->time('sleep_time')->default(Carbon::createFromTime(21, 0, 0)->toTimeString());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digimons');
    }
};
