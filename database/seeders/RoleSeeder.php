<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Administrator of the system',
                'created_by' => 1
            ],
            [
                'id' => 2,
                'name' => 'Member',
                'description' => 'Member of the system',
                'created_by' => 1
            ],
            [
                'id' => 6,
                'name' => 'Customer',
                'description' => 'Customer of the system',
                'created_by' => 1
            ],
        ];

        DB::table('role')->insert($roles);
    }
}
