<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;

class GrAccountRequest extends FormRequest
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
        $grAccount = $this->route('id');

        return [
            'gr_accounts' => 'array', 
            'gr_accounts.*.group_id' => 'nullable|exists:organization_groups,id',
            'gr_accounts.*.company_id' => 'required|exists:organization_companies,id',
            'gr_accounts.*.organization_id' => 'required|exists:organizations,id', 
            'gr_accounts.*.ledger_group_id' => 'nullable|exists:erp_groups,id',
            'gr_accounts.*.ledger_id' => 'required|exists:erp_ledgers,id',
            'gr_accounts.*.category_id' => 'nullable|exists:erp_categories,id',
            'gr_accounts.*.sub_category_id' => 'nullable|exists:erp_categories,id', 
            'gr_accounts.*.item_id' => 'nullable|array', 
            'gr_accounts.*.item_id.*' => 'exists:erp_items,id', 
            'gr_accounts.*.book_id' => 'nullable|exists:erp_books,id',
        ];
    }
    public function messages()
    {
        return [
            'gr_accounts.*.group_id.exists' => 'The selected group does not exist.',
            'gr_accounts.*.company_id.exists' => 'The selected company does not exist.',
            'gr_accounts.*.organization_id.exists' => 'The selected organization does not exist.',
            'gr_accounts.*.ledger_group_id.exists' => 'The selected ledger group does not exist.',
            'gr_accounts.*.ledger_id.exists' => 'The selected ledger does not exist.',
            'gr_accounts.*.category_id.exists' => 'The selected category does not exist.',
            'gr_accounts.*.sub_category_id.exists' => 'The selected sub-category does not exist.',
            'gr_accounts.*.item_id.exists' => 'The selected item does not exist.',
            'gr_accounts.*.book_id.exists' => 'The selected book does not exist.',
        ];
    }
}
