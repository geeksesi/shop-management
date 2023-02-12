<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id()->nullable();;
            $table->timestamps();
            $table->string("title");
            $table->text("body");
            $table->text("thumbnail");
            $table->text("seo_description");
            $table->string("tags");

            $table->unsignedBigInteger('id')->nullable();
            $table->foreign('id')->references('id')->on('blog_category')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
};
