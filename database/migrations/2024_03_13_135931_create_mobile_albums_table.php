<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->integer('transition_in')->default(1);
            $table->integer('transition_out')->default(2);
            $table->integer('transition')->default(6);
            $table->string('type', 150)->default('sub_banner');
            $table->string('banner_type')->default('image');
            $table->integer('user_id')->default(1);
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
        Schema::dropIfExists('mobile_albums');
    }
}
