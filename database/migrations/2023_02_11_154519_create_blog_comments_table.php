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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger('BlogPost_id');
            $table->foreign('BlogPost_id')->references('id')->on('blog_posts')->onDelete('cascade');
            $table->timestamps();
            $table->text("description");
            $table->string("author_name")->nullable()->comment("if not logged in");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
};
