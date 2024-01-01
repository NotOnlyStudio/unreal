<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string("title",350);
            $table->string("alias",350);
            $table->unsignedBigInteger("forum_section_id");
            $table->foreign('forum_section_id')->references('id')->on('forum_sections')->onDelete('cascade');
            $table->integer("countTopic")->default(0);
            $table->integer("countPosts")->default(0);
            $table->integer("inFavorites")->default(0);
            $table->boolean("showing")->default(true);
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
        Schema::dropIfExists('forums');
    }
}
