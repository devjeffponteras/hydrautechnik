<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->string('book_type')->nullable();
            $table->enum('type', ['physical', 'ebook'])->default('physical');
            $table->string('sku', 250)->nullable();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('slug');
            $table->string('file_url');
            $table->decimal('ebook_price',16,4)->nullable();
            $table->decimal('ebook_dicounted_price',16,4)->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price',16,4)->nullable();
            $table->decimal('dicounted_price',16,4)->nullable();
            $table->decimal('reorder_point',16,2)->default(0.00);
            $table->string('size', 30)->nullable();
            $table->string('weight')->nullable();
            $table->string('texture')->nullable();
            $table->string('status',100);
            $table->string('uom',30)->default('PC');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->date('publication_date')->nullable();
            $table->integer('created_by');
            $table->string('meta_title', 150)->nullable();
            $table->string('meta_keyword', 150)->nullable();
            $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('products');
    }
}
