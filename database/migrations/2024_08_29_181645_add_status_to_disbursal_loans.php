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
        Schema::table('erp_disbursal_loans', function (Blueprint $table) {
            $table->integer('status')->default(0)->comment('pending=0, process=1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('erp_disbursal_loans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
