<?php

use App\Helpers\ConstantHelper;
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
        Schema::create('erp_sale_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('series_id')->nullable(); 
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('sale_order_no')->unique()->nullable();
            $table->date('so_date')->nullable();
            $table->integer('revision_no')->default(0);
            $table->integer('revision_date')->nullable()->default(NULL);
            $table->string('so_type')->default('direct');
            $table->string('billing_to')->nullable();
            $table->string('ship_to')->nullable();
            $table->json('billing_address')->nullable(); 
            $table->json('shipping_address')->nullable();
            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('payment_terms_id')->nullable(); 
            $table->unsignedBigInteger('currency_id')->nullable(); 
            $table->decimal('sub_total', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->decimal('gst', 15, 2)->nullable();
            $table->json('gst_details')->nullable();
            $table->decimal('taxable_amount', 15, 2)->nullable();
            $table->decimal('other_expenses', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable(); 
            $table->text('final_remarks')->nullable();
            $table->enum('status', ConstantHelper::PURCHASE_ORDER_STATUS)->default(ConstantHelper::OPEN);
            $table->string('document_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_sale_orders');
    }
};
