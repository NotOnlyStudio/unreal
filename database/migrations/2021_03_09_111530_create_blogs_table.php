<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("author_id");
            $table->foreign("author_id")->references("id")->on("users")->onDelete("cascade");
            $table->string('alias',100);
            $table->string("title" ,256);
            $table->text("tags");
            $table->longText("content");
            $table->integer("views")->default(0);
            $table->boolean("moderation")->default(false);
            $table->json("meta")->nullable();
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
        Schema::dropIfExists('blogs');
    }
}
