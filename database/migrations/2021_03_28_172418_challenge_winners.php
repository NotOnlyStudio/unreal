<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChallengeWinners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_winners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("gallery_id");
            $table->foreign('gallery_id')->references('id')->on('galleries');
            $table->unsignedBigInteger("challenge_id");
            $table->foreign('challenge_id')->references('id')->on('challenges');
            $table->tinyInteger('prize_place')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
