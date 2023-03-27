<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player1_digimon_id');
            $table->unsignedBigInteger('player2_digimon_id');
            $table->unsignedBigInteger('winner_digimon_id')->nullable();
            $table->json('events');
            $table->timestamps();

            $table->foreign('player1_digimon_id')->references('id')->on('user_digimons');
            $table->foreign('player2_digimon_id')->references('id')->on('user_digimons');
            $table->foreign('winner_digimon_id')->references('id')->on('user_digimons');
        });
    }

    public function down()
    {
        Schema::dropIfExists('battles');
    }
};
