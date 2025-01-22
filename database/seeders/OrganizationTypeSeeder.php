<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrganizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $organizationTypes = [
            ['name' => 'Public Limited', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Private Limited', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Proprietor', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Partnership', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Small Enterprise', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Medium Enterprise', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('erp_organization_types')->insert($organizationTypes);
    }
}
