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

    #TODO check columns with Category Document #17 in github
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('parent_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
