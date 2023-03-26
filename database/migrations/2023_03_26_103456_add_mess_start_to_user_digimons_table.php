<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->timestamp('mess_start')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('user_digimons', function (Blueprint $table) {
            $table->dropColumn('mess_start');
        });
    }
};
