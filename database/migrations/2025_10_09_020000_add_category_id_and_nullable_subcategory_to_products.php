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
        Schema::table('products', function (Blueprint $table) {
            // Add category_id column
            $table->unsignedBigInteger('category_id')->nullable()->after('subcategory_id');

            // Make subcategory_id nullable
            $table->unsignedBigInteger('subcategory_id')->nullable()->change();

            // Add foreign key for category_id if you want (optional)
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key then column
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Make subcategory_id not nullable again
            $table->unsignedBigInteger('subcategory_id')->nullable(false)->change();
        });
    }
};
