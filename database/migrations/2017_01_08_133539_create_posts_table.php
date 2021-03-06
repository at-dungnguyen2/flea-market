<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('province_id')->unsigned();
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->integer('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->integer('ward_id')->unsigned();
            $table->foreign('ward_id')->references('id')->on('wards');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('title', 256);
            $table->integer('price')->nullable();
            $table->string('state', 45)->default('waiting');
            $table->string('type', 10); // buy or sell
            $table->string('phone', 40)->nullable();
            $table->string('address');
            $table->string('slug', 256);
            $table->text('description');
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
        Schema::dropIfExists('posts');
    }
}
