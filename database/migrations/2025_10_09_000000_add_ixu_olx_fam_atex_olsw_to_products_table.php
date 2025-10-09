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
            // Adding nullable string columns for IXU, OLX, FAM ATEX and OLSW
            $table->string('ixu')->nullable()->after('specification');
            $table->string('olx')->nullable()->after('ixu');
            $table->string('fam_atex')->nullable()->after('olx');
            $table->string('olsw')->nullable()->after('fam_atex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['ixu', 'olx', 'fam_atex', 'olsw']);
        });
    }
};
