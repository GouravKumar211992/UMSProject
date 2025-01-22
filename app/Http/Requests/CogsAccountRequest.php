<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;

class CogsAccountRequest extends FormRequest
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
        $cogsAccount = $this->route('id'); 

        return [
            'cogs_accounts' => 'array', 
            'cogs_accounts.*.group_id' => 'nullable|exists:organization_groups,id', 
            'cogs_accounts.*.company_id' => 'required|exists:organization_companies,id',
            'cogs_accounts.*.organization_id' => 'required|exists:organizations,id',
            'cogs_accounts.*.ledger_group_id' => 'nullable|exists:erp_groups,id', 
            'cogs_accounts.*.ledger_id' => 'required|exists:erp_ledgers,id', 
            'cogs_accounts.*.category_id' => 'nullable|exists:erp_categories,id',
            'cogs_accounts.*.sub_category_id' => 'nullable|exists:erp_categories,id', 
            'cogs_accounts.*.item_id' => 'nullable|array',
            'cogs_accounts.*.item_id.*' => 'exists:erp_items,id',
            'cogs_accounts.*.book_id' => 'nullable|exists:erp_books,id',
        ];
    }
    public function messages()
    {
        return [
            'cogs_accounts.*.group_id.exists' => 'The selected group does not exist.',
            'cogs_accounts.*.company_id.exists' => 'The selected company does not exist.',
            'cogs_accounts.*.organization_id.exists' => 'The selected organization does not exist.',
            'cogs_accounts.*.ledger_group_id.exists' => 'The selected ledger group does not exist.',
            'cogs_accounts.*.ledger_id.exists' => 'The selected ledger does not exist.',
            'cogs_accounts.*.category_id.exists' => 'The selected category does not exist.',
            'cogs_accounts.*.sub_category_id.exists' => 'The selected sub-category does not exist.',
            'cogs_accounts.*.item_id.exists' => 'The selected item does not exist.',
            'cogs_accounts.*.book_id.exists' => 'The selected book does not exist.',
        ];
    }
}
