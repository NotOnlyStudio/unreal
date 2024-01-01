<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->longText("content");
            $table->string("title", 350);
            $table->string("alias", 350);
            $table->text("tags");
            $table->unsignedBigInteger("forum_id");
            $table->foreign("forum_id")->references("id")->on("forums")->onDelete("cascade");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->integer("views")->default(0);
            $table->integer("ratingPlus")->default(0);
            $table->integer("ratingMinus")->default(0);
            $table->boolean("moderate")->default(false);
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
        Schema::dropIfExists('topics');
    }
}
