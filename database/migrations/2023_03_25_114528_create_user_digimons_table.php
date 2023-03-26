<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_digimons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('digimon_id');
            $table->string('name');
            $table->integer('exp')->default(0);
            $table->integer('age')->default(0);
            $table->integer('energy')->default(100);
            $table->integer('hunger')->default(100);
            $table->integer('weight')->default(10);
            $table->integer('training')->default(0);
            $table->integer('mess')->default(0);
            $table->boolean('is_asleep')->default(false);
            $table->boolean('is_dead')->default(false);
            $table->integer('care_mistakes')->default(0);
            $table->integer('battles')->default(0);
            $table->integer('battles_won')->default(0);
            $table->integer('overfeeds')->default(0);
            $table->integer('consecutive_feedings')->default(0);
            $table->timestamp('malnutrition_start')->nullable();
            $table->time('sleeping_hour')->default('21:00:00');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('digimon_id')->references('id')->on('digimons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_digimons');
    }
};
