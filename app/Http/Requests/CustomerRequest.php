<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use Auth;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected $organization_id;

    protected function prepareForValidation()
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $this->organization_id = $organization ? $organization->id : null;
        $this->group_id = $organization ? $organization->group_id : null; 
    }


    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $customerId = $this->route('id');

        return [
           'customer_code' => [
                'required',
                'string',
                'max:255',
                $isUpdate 
                    ? Rule::unique('erp_customers', 'customer_code')
                        ->where('group_id', $this->group_id)
                        ->whereNull('deleted_at')
                        ->ignore($customerId)
                    : Rule::unique('erp_customers', 'customer_code')
                        ->where('group_id', $this->group_id)
                        ->whereNull('deleted_at'),
            ],
            'organization_type_id' => 'required|exists:erp_organization_types,id',
            'customer_type' => 'required|string',
            'display_name' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:erp_categories,id',
            'subcategory_id' => 'nullable|exists:erp_categories,id',
            'sales_person_id'=>'nullable|exists:employees,id',
            'stop_billing' => 'nullable|string|max:255',
            'stop_purchasing' => 'nullable|string|max:255',
            'stop_payment' => 'nullable|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'company_id' => 'nullable|exists:companies,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'ledger_id' => 'nullable|exists:erp_ledgers,id',
            'ledger_group_id' => 'nullable|exists:erp_groups,id',
            'related_party' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                $isUpdate
                    ? Rule::unique('erp_customers', 'email')
                        ->where('group_id', $this->group_id)
                        ->whereNull('deleted_at')
                        ->ignore($customerId)
                    : Rule::unique('erp_customers', 'email')
                        ->where('group_id', $this->group_id)
                        ->whereNull('deleted_at'),
            ],
            'phone' => 'nullable|string|regex:/^\d{10,12}$/',
            'mobile' => 'nullable|string|regex:/^\d{10,12}$/',
            'whatsapp_number' => 'nullable|string|regex:/^\d{10,12}$/',
            'whatsapp_same_as_mobile' => 'nullable|string',
            'notification' => 'nullable|array',
            'notification.*' => 'nullable|string|max:255',
            'pan_number' => ['nullable', 'string', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'tin_number' => 'nullable|string|regex:/^\d{10}$/',
            'aadhar_number' => 'nullable|string|regex:/^\d{12}$/',
            'currency_id' => 'nullable|exists:currency,id',
            'opening_balance' => 'nullable|numeric|min:0',
            'pricing_type' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|integer|min:0|max:365',
            'interest_percent' => 'nullable|numeric|min:0',
            'payment_terms_id' => 'nullable|exists:erp_payment_terms,id',
            'other_documents.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhaar_attachment' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'pan_attachment' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'tin_attachment' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'nullable',

            // Bank validation
            'bank_info' => 'nullable|array',
            'bank_info.*.id' => 'nullable|integer|exists:erp_bank_infos,id',
            'bank_info.*.bank_name' => 'nullable|string|max:255',
            'bank_info.*.beneficiary_name' => 'nullable|string|max:255',
            'bank_info.*.account_number' => 'nullable|string|regex:/^[\d\s-]{1,255}$/',
            'bank_info.*.re_enter_account_number' => 'nullable|string|max:255|same:bank_info.*.account_number',
            'bank_info.*.ifsc_code' => 'nullable|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'bank_info.*.cancel_cheque.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bank_info.*.primary' => 'nullable',

            // Financial validation
            'financial' => 'nullable|array',
            'financial.customer_id' => 'nullable|exists:erp_customers,id',
            'financial.leader_id' => 'nullable|string|max:255',
            'financial.pricing_type' => 'nullable|string|max:255',
            'financial.credit_limit' => 'nullable|numeric|min:0',
            'financial.credit_days' => 'nullable|integer|min:0|max:365',
            'financial.interest_percent' => 'nullable|numeric|min:0',

            // Notes validation
            'notes' => 'nullable|array',
            'notes.remark' => 'nullable|string|max:255',

            // Customer Item
            'customer_item.*.item_id' => 'nullable', 
            'customer_item.*.item_code' => 'nullable|string|max:255',  
            'customer_item.*.item_name' => 'nullable|string|max:255',  
            'customer_item.*.item_details' => 'nullable|string|max:255',  
            'customer_item.*.sell_price' =>'nullable|regex:/^[0-9]*(\.[0-9]+)?$/|min:0', 
            'customer_item.*.uom_id' => 'nullable|exists:erp_units,id|string|max:255', 
            
            'contacts' => 'nullable|array',
            'contacts.*' => 'nullable|array',
            'contacts.*.salutation' => 'nullable|string|max:50',
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255', 
            'contacts.*.mobile' => 'nullable|string|regex:/^\d{10,12}$/',
            'contacts.*.phone' => 'nullable|string|regex:/^\d{10,12}$/',
            'contacts.*.primary' => 'nullable',

            // Address validation
            'addresses' => 'nullable|array',
            'addresses.*.country_id' => 'nullable|exists:countries,id',
            'addresses.*.state_id' => 'nullable|exists:states,id',
            'addresses.*.city_id' => 'nullable|exists:cities,id',
            'addresses.*.type' => 'nullable|string|max:255',
            'addresses.*.pincode' => 'nullable|string|max:10',
            'addresses.*.phone' => 'nullable|string|regex:/^\d{10,12}$/',
            'addresses.*.fax_number' => 'nullable|numeric|min:0|max:999999999999999',
            'addresses.*.address' => 'nullable|string|max:255',
            'addresses.*.is_billing' => 'nullable',
            'addresses.*.is_shipping' => 'nullable',

            // Compliance validation
            'compliance' => 'nullable|array',
            'compliance.country_id' => 'nullable|exists:countries,id',
            'compliance.tds_applicable' => 'nullable',
            'compliance.wef_date' => 'nullable|date',
            'compliance.tds_certificate_no' => 'nullable|string|max:255',
            'compliance.tds_tax_percentage' => 'nullable|numeric|max:100',
            'compliance.tds_category' => 'nullable|string|max:255',
            'compliance.tds_value_cab' => 'nullable|numeric',
            'compliance.tan_number' => 'nullable|string|max:255',
            'compliance.gst_applicable' => 'nullable',
            'compliance.gstin_no' => ['nullable', 'string', 'size:15', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'compliance.gst_registered_name' => 'nullable|string|max:255',
            'compliance.gstin_registration_date' => 'nullable|date',
            'compliance.msme_registered' => 'nullable',
            'compliance.msme_no' => 'nullable|string|max:255',
            'compliance.msme_type' => 'nullable|string|max:255',
            'compliance.status' => 'nullable|string|max:255',
            'compliance.gst_certificate.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'compliance.msme_certificate.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_code.required' => 'The customer code is required.',
            'customer_code.string' => 'The customer code must be a string.',
            'customer_code.max' => 'The customer code may not be greater than 255 characters.',
            'customer_code.unique' => 'The customer code has already been taken.',
            'credit_days.max' => 'The credit days must not exceed 365 days.',

            'organization_type_id.required' => 'The organization type is required.',
            'organization_type_id.exists' => 'The selected organization type is invalid.',
            
            'customer_type.required' => 'The customer type is required.',
            'customer_type.string' => 'The customer type must be a string.',
            
            'display_name.string' => 'The display name must be a string.',
            'display_name.max' => 'The display name may not be greater than 255 characters.',
            
            'company_name.required' => 'The company name is required.',
            'company_name.string' => 'The company name must be a string.',
            'company_name.max' => 'The company name may not be greater than 255 characters.',
            
            'category_id.string' => 'The category ID must be a string.',
            'subcategory_id.string' => 'The subcategory ID must be a string.',
            'stop_billing.string' => 'The stop billing field must be a string.',
            'stop_purchasing.string' => 'The stop purchasing field must be a string.',
            'stop_payment.string' => 'The stop payment field must be a string.',
            
            'group_id.exists' => 'The selected group is invalid.',
            'company_id.exists' => 'The selected company is invalid.',
            'organization_id.exists' => 'The selected organization is invalid.',
            'ledger_id.exists' => 'The selected ledger is invalid.',
            
            'related_party.string' => 'The related party must be a string.',
            'related_party.max' => 'The related party may not be greater than 255 characters.',

            'email.email' => 'The email address must be a valid email address.',
            'email.regex' => 'The email address format is invalid.',
            'email.unique' => 'The email has already been taken.',

            'phone.regex' => 'The phone number must be between 10 and 12 digits.',
            'mobile.regex' => 'The mobile number must be between 10 and 12 digits.',
            'whatsapp_number.regex' => 'The WhatsApp number must be between 10 and 12 digits.',
            'whatsapp_same_as_mobile.string' => 'The WhatsApp same as mobile field must be a string.',
            
            'notification.array' => 'The notification field must be an array.',
            'notification.*.string' => 'Each notification must be a string.',
            
            'pan_number.regex' => 'The PAN number must be in the format: AAAAA9999A.',
            'tin_number.regex' => 'The TIN number must be in the format: 9999999999.',
            'aadhar_number.regex' => 'The Aadhaar number must be in the format: 999999999999.',
            
            'currency_id.exists' => 'The selected currency is invalid.',

            'financial.customer_id.exists' => 'The customer ID must exist.',
            'financial.leader_id.string' => 'The leader ID must be a string.',
            'financial.pricing_type.string' => 'The pricing type must be a string.',
            'financial.credit_limit.numeric' => 'The credit limit must be a number.',
            'financial.credit_days.integer' => 'The credit days must be an integer.',
            'financial.credit_days.min' => 'The credit days must be at least 0.',
            'financial.credit_days.max' => 'The credit days must not exceed 365 days.',
            'financial.interest_percent.numeric' => 'The interest percentage must be a number.',

            'bank_info.array' => 'The bank info field must be an array.',
            'bank_info.*.id.exists' => 'The bank info ID must exist.',
            'bank_info.*.bank_name.string' => 'The bank name must be a string.',
            'bank_info.*.beneficiary_name.string' => 'The beneficiary name must be a string.',
            'bank_info.*.account_number.regex' => 'The account number must be a valid number.',
            'bank_info.*.re_enter_account_number.same' => 'The re-entered account number does not match.',
            'bank_info.*.ifsc_code.regex' => 'The IFSC code must be in the format XXXX0XXXXXX.',
             'bank_info.*.cancel_cheque.*.mimes' => 'The cancel cheque must be a file of type: pdf, jpg, jpeg, png.',
            
            'compliance.array' => 'The compliance field must be an array.',
            'compliance.tds_applicable.string' => 'The TDS applicable field must be a string.',
            'compliance.wef_date.date' => 'The WEF date must be a valid date.',
            'compliance.tds_certificate_no.string' => 'The TDS certificate number must be a string.',
            'compliance.tds_tax_percentage.numeric' => 'The TDS tax percentage must be a number.',
            'compliance.tds_tax_percentage.max' => 'The TDS tax percentage must not be greater than 100%.',
            'compliance.tds_category.string' => 'The TDS category must be a string.',
            'compliance.tds_value_cab.numeric' => 'The TDS value CAB must be a number.',
            'compliance.tan_number.string' => 'The TAN number must be a string.',
            
            'compliance.gst_applicable.string' => 'The GST applicable field must be a string.',
            'compliance.gstin_no.string' => 'The GSTIN number must be a valid 15 character string.',
            'compliance.gstin_no.regex' => 'The GSTIN number format is invalid.',
            'compliance.gstin_registration_date.date' => 'The GSTIN registration date must be a valid date.',
            'compliance.msme_registered.string' => 'The MSME registered field must be a string.',
            'compliance.msme_no.string' => 'The MSME number must be a string.',
            'compliance.msme_type.string' => 'The MSME type must be a string.',
            
            'compliance_certificates.*.name.string' => 'The certificate name must be a string.',
            'compliance_certificates.*.certificate_no.string' => 'The certificate number must be a string.',
            'compliance_certificates.*.expiry_date.date' => 'The certificate expiry date must be a valid date.',
            'compliance_certificates.*.document.mimes' => 'The certificate document must be a file of type: pdf, jpg, jpeg, png.',
            'compliance.gst_certificate.*.mimes' => 'The GST certificate must be a file of type: jpg, jpeg, png, pdf.',
            'compliance.msme_certificate.*.mimes' => 'The MSME certificate must be a file of type: jpg, jpeg, png, pdf.',
            'compliance.gstin_no.size' => 'The GSTIN number must be exactly 15 characters long.',
            'compliance.gstin_no.regex' => 'The GSTIN number must be in the format: 12ABCDE1234F1Z5.',
            
            'addresses.array' => 'The addresses field must be an array.',
            'addresses.*.country_id.exists' => 'The selected country is invalid.',
            'addresses.*.state_id.exists' => 'The selected state is invalid.',
            'addresses.*.city_id.exists' => 'The selected city is invalid.',
            'addresses.*.type.string' => 'The address type must be a string.',
            'addresses.*.pincode.string' => 'The pincode must be a string.',
            'addresses.*.pincode.max' => 'The pincode may not be greater than 10 characters.',
            'addresses.*.phone.regex' => 'The address phone number must be between 10 and 12 digits.',
            'addresses.*.fax_number.numeric' => 'The fax number must be a numeric value.',
            'addresses.*.fax_number.min' => 'The fax number must be at least 0.',
            'addresses.*.fax_number.max' => 'The fax number must not exceed 15 digits.',
            'addresses.*.address.string' => 'The address must be a string.',
            'addresses.*.address.max' => 'The address may not be greater than 255 characters.',

            'contacts.array' => 'The contacts field must be an array.',
            'contacts.*.array' => 'Each contact must be an array.',
            'contacts.*.salutation.string' => 'The salutation must be a string.',
            'contacts.*.salutation.max' => 'The salutation may not be greater than 50 characters.',
            'contacts.*.name.string' => 'The name must be a string.',
            'contacts.*.name.max' => 'The name may not be greater than 255 characters.',
            'contacts.*.email.email' => 'The email must be a valid email address.',
            'contacts.*.email.max' => 'The email may not be greater than 255 characters.',
            'contacts.*.mobile.string' => 'The mobile number must be a string.',
            'contacts.*.mobile.regex' => 'The mobile number must be between 10 to 12 digits.',
            'contacts.*.phone.string' => 'The phone number must be a string.',
            'contacts.*.phone.regex' => 'The phone number must be between 10 to 12 digits.',

            'other_documents.*.mimes' => 'Other documents must be in one of the following formats: pdf, jpg, jpeg, png.',
            'aadhaar_attachment.mimes' => 'The Aadhaar attachment must be in one of the following formats: pdf, jpg, jpeg, png.',
            'pan_attachment.mimes' => 'The PAN attachment must be in one of the following formats: pdf, jpg, jpeg, png.',
            'tin_attachment.mimes' => 'The TIN attachment must be in one of the following formats: pdf, jpg, jpeg, png.',
            'bank_info.*.cancel_cheque.*.mimes' => 'The cancel cheque must be in one of the following formats: pdf, jpg, jpeg, png.',
            'compliance.gst_certificate.*.mimes' => 'The GST certificate must be in one of the following formats: jpg, jpeg, png, pdf.',
            'compliance.msme_certificate.*.mimes' => 'The MSME certificate must be in one of the following formats: jpg, jpeg, png, pdf.',
            'other_documents.*.max' => 'The size of other documents must not exceed 2 MB.',
            'aadhaar_attachment.max' => 'The size of the Aadhaar attachment must not exceed 2 MB.',
            'pan_attachment.max' => 'The size of the PAN attachment must not exceed 2 MB.',
            'tin_attachment.max' => 'The size of the TIN attachment must not exceed 2 MB.',
            'bank_info.*.cancel_cheque.*.max' => 'The size of the cancel cheque must not exceed 2 MB.',
            'compliance.gst_certificate.*.max' => 'The size of the GST certificate must not exceed 2 MB.',
            'compliance.msme_certificate.*.max' => 'The size of the MSME certificate must not exceed 2 MB.',

            
        ];
    }

}
