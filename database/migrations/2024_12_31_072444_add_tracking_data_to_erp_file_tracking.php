<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE erp_file_tracking CHANGE COLUMN file_number file_name VARCHAR(255)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE erp_file_tracking CHANGE COLUMN file_name file_number VARCHAR(255)');
    }
};
