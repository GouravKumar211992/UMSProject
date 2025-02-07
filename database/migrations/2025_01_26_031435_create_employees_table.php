<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('device_person_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('sub_loc_id')->nullable();
            $table->unsignedBigInteger('loc_type_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('father_name')->nullable();
            $table->text('geo_address')->nullable();
            $table->text('temporary_geo_address')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('password');
            $table->string('employee_code')->nullable();
            $table->unsignedBigInteger('employee_type_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sub_department_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('guarantor_id')->nullable();
            $table->unsignedBigInteger('contractor_id')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->string('workman_identity')->nullable();
            $table->integer('total_leaves')->default(0);
            $table->string('face_id')->nullable();
            $table->string('system_face_id')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('dial_code')->nullable();
            $table->string('sos_call')->nullable();
            $table->boolean('live_tracking')->default(false);
            $table->boolean('check_in')->default(false);
            $table->boolean('flexi_hours')->default(false);
            $table->boolean('with_face')->default(false);
            $table->string('fcm_token')->nullable();
            $table->json('sms_setting')->nullable();
            $table->json('email_setting')->nullable();
            $table->json('push_setting')->nullable();
            $table->string('premises')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete field
            $table->string('employee_type')->nullable();
            $table->boolean('can_reset')->default(false);
            $table->date('date_of_joining')->nullable();
            $table->string('imei')->nullable();
            $table->boolean('imei_enable')->default(false);
            $table->string('remember_token', 100)->nullable();
            $table->string('workman_identity_type')->nullable();
            $table->string('user_type')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contractual'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced'])->nullable();
            $table->date('date_exit')->nullable();
            $table->date('transfer_date')->nullable();
            $table->string('transferred_from')->nullable();
            $table->boolean('device_sync')->default(false);
            $table->boolean('free_location')->default(false);
            $table->text('imagebase64code')->nullable();
            $table->string('device_image')->nullable();
            $table->string('card_no')->nullable();
            $table->boolean('vip')->default(false);
            $table->integer('max_hour')->default(0);
            $table->boolean('is_app_login')->default(false);
            $table->boolean('is_attendance_access')->default(false);
            $table->boolean('is_app_in_out')->default(false);
            $table->string('pf_no')->nullable();
            $table->string('uan_number')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('name_as_per_aadhar')->nullable();
            $table->string('nationality')->nullable();
            $table->boolean('disability')->default(false);
            $table->string('qualification')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_valid_from_date')->nullable();
            $table->date('passport_valid_to_date')->nullable();
            $table->string('esic_no')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->date('date_of_leaving')->nullable();
            $table->integer('no_of_child')->default(0);
            $table->boolean('metrocity')->default(false);
            $table->string('cityzen_type')->nullable();
            $table->string('working_hour')->nullable();
            $table->string('alternate_mobile')->nullable();
            $table->unsignedBigInteger('parent_area_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->text('remarks')->nullable();
            $table->date('blacklist_date')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_mobile')->nullable();
            $table->string('contact_person_relation')->nullable();
            $table->string('pan_no')->nullable();
            $table->boolean('is_bus_allowed')->default(false);
            $table->unsignedBigInteger('bus_id')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('attendance_data_removal')->default(false);
            $table->string('contact_person_dial_code')->nullable();
            $table->boolean('disable_employee')->default(false);
            $table->integer('login_attempts')->default(0);
            $table->timestamp('last_login_attempt')->nullable();
            $table->string('ip_number')->nullable();
            $table->unsignedBigInteger('time_zone_id')->nullable();
            $table->unsignedBigInteger('work_station_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('company_code_id')->nullable();
            $table->unsignedBigInteger('company_pf_code_id')->nullable();
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->date('inactivation_date')->nullable();
            $table->json('configuration')->nullable();
            
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            // Define more foreign keys as needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
