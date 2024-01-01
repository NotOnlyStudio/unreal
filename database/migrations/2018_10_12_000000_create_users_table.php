<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("api_token")->default("null");
            $table->string('name');
            $table->string("specialization", 450)->nullable();
            $table->string("website",500)->nullable();
            $table->text("signature")->nullable();
            $table->string('photo', 300)->nullable();
            $table->string("location",300)->nullable();
            $table->mediumInteger("rating")->default(0);
            $table->string("google_id")->nullable();
            $table->string("facebook_id")->nullable();
            $table->string("twitter_id")->nullable();
            $table->unsignedBigInteger("rang_id")->default(1);
            $table->foreign("rang_id")->references("id")->on("rangs");
            $table->string('email');
            $table->boolean("isAdmin")->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->text("email_verification_token")->nullable();
            $table->string('password');
            $table->smallInteger("free_models")->default(3);
            $table->smallInteger("models_count")->default(5);
            $table->boolean("ban")->default(false);
            $table->boolean('mails')->default(false);
            $table->boolean('verified')->default(false);
            $table->string("permisions",50)->default("USER");
            $table->rememberToken();
            $table->text('stripe_account')->nullable();
            $table->text('stripe_link')->nullable();
            $table->boolean('stripe_success')->default(false);
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
        Schema::dropIfExists('users');
    }
}
