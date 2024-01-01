<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title", 350);
            $table->text("alias");
            $table->text('tags')->nullable();
            $table->unsignedBigInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer("category_id");
            $table->string("subcategory_id",100);
            $table->float("price")->nullable();
            $table->string("filename", 100);
            $table->text("photos");
            $table->text("props")->nullable();
            $table->json("meta")->nullable();
            $table->integer("purchases_count")->default(0);
            $table->integer("in_cart")->default(0);
            $table->boolean("moderation")->default(false);
            $table->boolean("is_free")->default(false);
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
        Schema::dropIfExists('products');
    }
}
