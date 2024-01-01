<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_counters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->default(0);
            $table->bigInteger("messages_count")->default(0);
            $table->bigInteger("forum_count")->default(0);
            $table->bigInteger("profit_count")->default(0);
            $table->bigInteger("notifications_count")->default(0);
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
        Schema::dropIfExists('notification_counters');
    }
}
