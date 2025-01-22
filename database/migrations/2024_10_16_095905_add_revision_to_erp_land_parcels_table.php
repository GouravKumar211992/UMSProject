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
        Schema::table('erp_land_parcels', function (Blueprint $table) {
            $table->integer('revision_number')->default(0)->after('approvalStatus'); // Adjust 'approvalStatus' to the appropriate column name
            $table->date('revision_date')->nullable()->after('revision_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('erp_land_parcels', function (Blueprint $table) {
            $table->dropColumn('revision_number');
            $table->dropColumn('revision_date');
        });
    }
};
