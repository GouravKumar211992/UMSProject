<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;

class StockAccountRequest extends FormRequest
{

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
        $stockAccount = $this->route('id');

        return [
            'stock_accounts' => 'array', 
            'stock_accounts.*.group_id' => 'nullable|exists:organization_groups,id',
            'stock_accounts.*.company_id' => 'required|exists:organization_companies,id',
            'stock_accounts.*.organization_id' => 'required|exists:organizations,id',
            'stock_accounts.*.ledger_group_id' => 'nullable|exists:erp_groups,id',
            'stock_accounts.*.ledger_id' => 'required|exists:erp_ledgers,id',
            'stock_accounts.*.category_id' => 'nullable|exists:erp_categories,id',
            'stock_accounts.*.sub_category_id' => 'nullable|exists:erp_categories,id',
            'stock_accounts.*.item_id' => 'nullable|array',
            'stock_accounts.*.item_id.*' => 'exists:erp_items,id',
            'stock_accounts.*.book_id' => 'nullable|exists:erp_books,id',
        ];
        
    }

    public function messages()
    {
        return [
        'stock_accounts.*.group_id.exists' => 'The selected group does not exist.',
        'stock_accounts.*.company_id.exists' => 'The selected company does not exist.',
        'stock_accounts.*.organization_id.exists' => 'The selected organization does not exist.',
        'stock_accounts.*.ledger_group_id.exists' => 'The selected ledger group does not exist.',
        'stock_accounts.*.ledger_id.exists' => 'The selected ledger does not exist.',
        'stock_accounts.*.category_id.exists' => 'The selected category does not exist.',
        'stock_accounts.*.sub_category_id.exists' => 'The selected sub-category does not exist.',
        'stock_accounts.*.item_id.exists' => 'The selected item does not exist.',
        'stock_accounts.*.book_id.exists' => 'The selected book does not exist.',
        ];
    }
}
