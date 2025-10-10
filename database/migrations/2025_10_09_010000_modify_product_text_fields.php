<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw ALTER statements which don't require doctrine/dbal
        // Works for MySQL and MariaDB. If you're using a different driver, adjust accordingly.
        DB::statement("ALTER TABLE `products` MODIFY `ixu` TEXT NULL");
        DB::statement("ALTER TABLE `products` MODIFY `olx` TEXT NULL");
        DB::statement("ALTER TABLE `products` MODIFY `fam_atex` TEXT NULL");
        DB::statement("ALTER TABLE `products` MODIFY `olsw` TEXT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to VARCHAR(255)
        DB::statement("ALTER TABLE `products` MODIFY `ixu` VARCHAR(255) NULL");
        DB::statement("ALTER TABLE `products` MODIFY `olx` VARCHAR(255) NULL");
        DB::statement("ALTER TABLE `products` MODIFY `fam_atex` VARCHAR(255) NULL");
        DB::statement("ALTER TABLE `products` MODIFY `olsw` VARCHAR(255) NULL");
    }
};
