<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string("name", 500);
            $table->string("alias",350);
            $table->json("photos")->nullable();
            $table->string("challenge_photo",350)->nullable();
            $table->text("about");
            $table->longText("description");
            $table->date("deadline");
            $table->longText("requirments");
            $table->text("moderation");
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
        Schema::dropIfExists('challenges');
    }
}
