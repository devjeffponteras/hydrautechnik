<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_ads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('file_url')->default(null);
            $table->text('mobile_file_url')->default(null);
            $table->text('url')->default(null);
            $table->tinyInteger('status')->default(0);
            $table->integer('click_counts')->default(0);
            $table->date('expiration_date')->nullable();
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
        Schema::dropIfExists('banner_ads');
    }
}
