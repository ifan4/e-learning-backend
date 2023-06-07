<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('materi_id');
            $table->foreign('materi_id')->references('id')->on('materis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_scores', function (Blueprint $table) {
            //
        });
    }
};
