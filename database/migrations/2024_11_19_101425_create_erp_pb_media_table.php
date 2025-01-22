<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\ConstantHelper;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('erp_pb_media', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->uuid()->nullable()->unique();
            $table->string('model_name');
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
        });

        if (Schema::hasColumn('erp_pb_headers', 'attachment')) {
            Schema::table('erp_pb_headers', function (Blueprint $table) {
                $table->dropColumn('attachment');
            });
        }
        if (Schema::hasColumn('erp_pb_header_histories', 'attachment')) {
            Schema::table('erp_pb_header_histories', function (Blueprint $table) {
                $table->dropColumn('attachment');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_pb_media');
        if (!Schema::hasColumn('erp_pb_headers', 'attachment')) {
            Schema::table('erp_pb_headers', function (Blueprint $table) {
                $table->json('attachment')->nullable();
            });
        }
        if (!Schema::hasColumn('erp_pb_header_histories', 'attachment')) {
            Schema::table('erp_pb_header_histories', function (Blueprint $table) {
                $table->json('attachment')->nullable();
            });
        }
    }
};
