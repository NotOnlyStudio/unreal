<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("chat_id");
            $table->foreign("chat_id")->references("id")->on("chats")->onDelete("cascade");
            $table->longText("message_body");
            $table->bigInteger("message_from");
            $table->boolean("view")->default(false);
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
        Schema::dropIfExists('messages');
    }
}
