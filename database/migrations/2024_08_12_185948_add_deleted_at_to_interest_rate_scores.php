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
        Schema::table('erp_interest_rate_scores', function (Blueprint $table) {
            $table->softDeletes()->after('interest_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('erp_interest_rate_scores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
