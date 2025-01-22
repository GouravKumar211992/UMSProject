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
        Schema::create('erp_boms_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('book_id')->comment('books tbl id')->nullable();
            $table->string('book_code')->comment('books tbl')->nullable();
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->string('document_status')->nullable();
            $table->integer('revision_number')->default(0);
            $table->date('revision_date')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('qty_produced')->default(1)->comment('Quantity Produced');
            $table->decimal('total_item_value', 15, 2)->default(0.00);
            $table->decimal('item_waste_amount', 15, 2)->default(0.00);
            $table->decimal('item_overhead_amount', 15, 2)->default(0.00);
            $table->decimal('header_waste_perc', 15, 2)->default(0.00);
            $table->decimal('header_waste_amount', 15, 2)->default(0.00);
            $table->decimal('header_overhead_amount', 15, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->enum('status', ConstantHelper::STATUS)->default(ConstantHelper::ACTIVE);
            $table->integer('approval_level')->default(1)->comment('Current Approval Level');

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('erp_bom_details_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('item_code')->nullable();
            $table->integer('qty')->nullable()->comment('Consumption');
            $table->decimal('item_cost', 15, 2)->default(0.00);
            $table->decimal('item_value', 15, 2)->default(0.00);
            $table->decimal('superceeded_cost', 15, 2)->default(0.00);
            $table->decimal('waste_perc', 15, 2)->default(0.00)->comment('waste percentage');
            $table->decimal('waste_amount', 15, 2)->default(0.00);
            $table->decimal('overhead_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2)->default(0.00);

            $table->string('section_name')->nullable();
            $table->unsignedBigInteger('sub_section_id')->nullable()->comment('erp_product_section_details id');
            $table->string('sub_section_name')->nullable();
            $table->unsignedBigInteger('station_id')->nullable();
            $table->string('station_name')->nullable();
            $table->text('remark')->nullable();

            $table->timestamps();

            $table->foreign('bom_id')->references('id')->on('erp_boms_history')->onDelete('cascade');
        });

        Schema::create('erp_bom_attributes_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('bom_detail_id')->nullable();
            $table->unsignedBigInteger('item_attribute_id')->nullable()->comment('use tbl erp_item_attributes');
            $table->enum('type', ['H', 'D'])->nullable()->comment('Attr Header or Detail level');
            $table->string('item_code')->nullable();
            $table->string('attribute_name')->nullable();
            $table->string('attribute_value')->nullable();
            $table->timestamps();

            $table->foreign('bom_detail_id')->references('id')->on('erp_bom_details_history')->onDelete('cascade');
            $table->foreign('bom_id')->references('id')->on('erp_boms_history')->onDelete('cascade');
        });

        Schema::create('erp_bom_overheads_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->unsignedBigInteger('bom_detail_id')->nullable();
            $table->enum('type', ['H', 'D'])->nullable()->comment('Attr Header or Detail level');
            $table->string('overhead_description')->nullable();
            $table->string('ledger_name')->nullable();
            $table->decimal('overhead_amount', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('bom_detail_id')->references('id')->on('erp_bom_details_history')->onDelete('cascade');
            $table->foreign('bom_id')->references('id')->on('erp_boms_history')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_bom_overheads_history');
        Schema::dropIfExists('erp_bom_attributes_history');
        Schema::dropIfExists('erp_bom_details_history');
        Schema::dropIfExists('erp_boms_history');
    }
};
