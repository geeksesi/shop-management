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
        Schema::create('BlogCategories_BlogPosts', function (Blueprint $table) {
            $table->unsignedBigInteger('BlogPost_id');
            $table->foreign('BlogPost_id')->references('id')->on('blog_posts')->onDelete('cascade');

            $table->unsignedBigInteger('BlogCategory_id');
            $table->foreign('BlogCategory_id')->references('id')->on('blog_categories')->onDelete('cascade');

            $table->unique(['BlogPost_id' , 'BlogCategory_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('BlogCategories_BlogPosts');
    }
};
