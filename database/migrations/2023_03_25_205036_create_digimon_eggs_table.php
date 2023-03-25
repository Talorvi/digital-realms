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
        Schema::create('digimon_eggs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('starter_digimon_id');
            $table->timestamps();

            $table->foreign('starter_digimon_id')->references('id')->on('digimons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digimon_eggs');
    }
};
