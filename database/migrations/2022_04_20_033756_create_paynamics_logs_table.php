<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaynamicsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paynamics_logs', function (Blueprint $table) {
            $table->id();
            $table->text('result_return')->nullable();
            $table->string('request_id', 32)->nullable();
            $table->string('response_id', 32)->nullable();
            $table->string('response_title')->nullable();
            $table->string('response_code', 5)->nullable();
            $table->string('response_message', 100)->nullable();
            $table->string('response_advise', 150)->nullable();
            $table->string('timestamp')->nullable();
            $table->string('ptype', 20)->nullable();
            $table->string('rebill_id', 50)->nullable();
            $table->string('token_id', 32)->nullable();
            $table->string('token_info', 20)->nullable();
            $table->string('processor_response_id', 60)->nullable();
            $table->string('processor_response_authcode')->nullable();
            $table->string('signature', 200)->nullable();
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
        Schema::dropIfExists('paynamics_logs');
    }
}
