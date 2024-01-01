<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("challenge_id")->nullable();
            // $table->foreign("challenge_id")->references("id")->on("challenges");
            $table->string("title", 300);
            $table->string("alias",300);
            $table->text("video")->nullable();
            $table->json("meta")->nullable();
            $table->longText("description");
            $table->text("tags");
            $table->text("photos");
            $table->boolean("moderation")->default(false);
            $table->integer("ratingPlus")->default(0);
            $table->integer("ratingMinus")->default(0);
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
        Schema::dropIfExists('galleries');
    }
}
