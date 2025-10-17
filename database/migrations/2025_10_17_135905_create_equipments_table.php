<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipments');
    }
};