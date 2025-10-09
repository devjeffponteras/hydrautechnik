<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->notNullable();
            $table->unsignedBigInteger('subcategory_id');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('specification')->nullable();
            $table->timestamps();

            $table->foreign('subcategory_id')->references('id')->on('product_subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
