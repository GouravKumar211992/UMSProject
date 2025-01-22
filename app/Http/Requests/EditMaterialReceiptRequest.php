<?php

namespace App\Http\Requests;

use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Models\NumberPattern;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class EditMaterialReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        // $bomId = $this->route('id');
        $rules = [
            'book_id' => 'required',
            'document_number' => 'nullable|max:50', // Default rule for document_number
            'vendor_id' => 'nullable',
            'currency_id' => 'nullable',
            'payment_term_id' => 'nullable',
            'gate_entry_no' => 'nullable|max:50',
            'gate_entry_date' => 'nullable|date',
            'eway_bill_no' => 'nullable|max:50',
            'consignment_no' => 'nullable|max:50',
            'supplier_invoice_no' => 'nullable|max:50',
            'supplier_invoice_date' => 'nullable|date',
            'transporter_name' => 'nullable|max:50',
            'vehicle_no' => 'nullable|max:50',
            'remarks' => 'nullable|max:500',
        ];

        // Check the condition only if book_id is present
        if ($this->filled('book_id')) {
            $user = Helper::getAuthenticatedUser();
            $numPattern = NumberPattern::where('organization_id', $user->organization_id)
                        ->where('book_id', $this->book_id)
                        ->orderBy('id', 'DESC')
                        ->first();

            // Update document_number rule based on the condition
            if ($numPattern && $numPattern->series_numbering == 'Manually') {
                $rules['document_number'] = 'nullable|unique:erp_mrn_headers,document_number';
            }
        }

        $rules['component_item_name.*'] = 'required';
        $rules['components.*.order_qty'] = 'required|numeric';
        $rules['components.*.accepted_qty'] = 'required|numeric';
        $rules['components.*.rate'] = 'required|numeric';
        $rules['components.*.store_id'] = 'required|numeric';
        $rules['components.*.remark'] = 'nullable|max:250';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'The series is required.',
            'gate_entry_no.required' => 'Gate Entry No is required.',
            'gate_entry_date.required' => 'Gate Entry Date is required.',
            'eway_bill_no.required' => 'Eway Bill No is required.',
            'consignment_no.required' => 'Consignment No is required.',
            'supplier_invoice_no.required' => 'Supplier Invoice No is required.',
            'supplier_invoice_date.required' => 'Supplier Invoice Date is required.',
            'transporter_name.required' => 'Vehicle No is required.',
            'vehicle_no.required' => 'The series is required.',
            'remarks.required' => 'Remark is required.',
            'item_code.required' => 'The product code is required.',
            'status.required' => 'The status field is required.',
            'uom_id' => 'The unit of measure must be a string.',
            'component_item_name.*.required' => 'Required',
            'components.*.order_qty.required' => 'Order Qty is required',
            'components.*.accepted_qty.required' => 'Accepted Qty is required',
            'components.*.rate.required' => 'Rate is required',
            'components.*.store_id.required' => 'Store is required',
            'components.*.attr_group_id.*.attr_name.required' => 'Select Attribute',
            'components.*.order_qty.numeric' => 'Order Qty must be integer',
            'components.*.accepted_qty.numeric' => 'Accepted Qty must be integer',
            'components.*.rate.numeric' => 'Rate must be integer',
        ];
 
    }
}
