<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Helper;

class SalesAccountRequest extends FormRequest
{
    public function authorize()
    {

        return true; 
    }

    protected $organization_id;
    protected $group_id;

    protected function prepareForValidation()
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $this->organization_id = $organization ? $organization->id : null;
        $this->group_id = $organization ? $organization->group_id : null;
    }

    public function rules()
    {
        $salesAccount = $this->route('id');

        return [
            'sales_accounts' => 'array',
            'sales_accounts.*.group_id' => 'nullable|exists:organization_groups,id', 
            'sales_accounts.*.company_id' => 'nullable|exists:organization_companies,id',
            'sales_accounts.*.organization_id' => 'nullable|exists:organizations,id', 
            'sales_accounts.*.customer_category_id' => 'nullable|exists:erp_categories,id', 
            'sales_accounts.*.customer_sub_category_id' => 'nullable|exists:erp_categories,id',
            'sales_accounts.*.customer_id' => 'nullable|exists:erp_customers,id', 
            'sales_accounts.*.item_category_id' => 'nullable|exists:erp_categories,id', 
            'sales_accounts.*.item_sub_category_id' => 'nullable|exists:erp_categories,id', 
            'sales_accounts.*.item_id' => 'nullable|array',
            'sales_accounts.*.item_id.*' => 'exists:erp_items,id', 
            'sales_accounts.*.book_id' => 'nullable|exists:erp_books,id',
            'sales_accounts.*.book_code' => 'nullable|string',
            'sales_accounts.*.ledger_group_id' => 'nullable|exists:erp_groups,id', 
            'sales_accounts.*.ledger_id' => 'nullable|exists:erp_ledgers,id',
            'sales_accounts.*.status' => 'nullable|in:active,inactive', 
        ];
    }

    public function messages()
    {
        return [
            'sales_accounts.array' => 'Sales accounts should be provided as an array.',
            'sales_accounts.*.group_id.exists' => 'The selected group does not exist.',
            'sales_accounts.*.company_id.exists' => 'The selected company does not exist.',
            'sales_accounts.*.organization_id.exists' => 'The selected organization does not exist.',
            'sales_accounts.*.customer_category_id.exists' => 'The selected customer category does not exist.',
            'sales_accounts.*.customer_sub_category_id.exists' => 'The selected customer sub-category does not exist.',
            'sales_accounts.*.customer_id.exists' => 'The selected customer does not exist.',
            'sales_accounts.*.item_category_id.exists' => 'The selected item category does not exist.',
            'sales_accounts.*.item_sub_category_id.exists' => 'The selected item sub-category does not exist.',
            'sales_accounts.*.item_id.exists' => 'The selected item does not exist.',
            'sales_accounts.*.book_id.exists' => 'The selected book does not exist.',
            'sales_accounts.*.book_code.string' => 'The book code must be a string.',
            'sales_accounts.*.ledger_group_id.exists' => 'The selected ledger group does not exist.',
            'sales_accounts.*.ledger_id.exists' => 'The selected ledger does not exist.',
            'sales_accounts.*.status.in' => 'The status must be either "active" or "inactive".',
        ];
    }

}
