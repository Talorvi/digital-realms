<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->index('is_asleep');
            $table->index('is_dead');
        });
    }

    public function down()
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->dropIndex(['is_asleep']);
            $table->dropIndex(['is_dead']);
        });
    }
};
