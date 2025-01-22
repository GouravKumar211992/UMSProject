<?php

namespace App\Http\Controllers\Ledger;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use App\Models\Group;
use App\Models\Ledger;
use App\Models\UserOrganizationMapping;
use App\Models\Organization;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user =  Helper::getAuthenticatedUser();
        $organizationId = $user->organization_id;

        if ($request->ajax()) {
            $organizations = [];


            if ($request->filter_organization && is_array($request->filter_organization)) {
                // Loop through the filter_organization array and push each value to $organizations
                foreach ($request->filter_organization as $value) {
                    $organizations[] = $value;  // Push each value to $organizations
                }
            }
            if (count($organizations) == 0) {
                $organizations[] = $organizationId;
            }

            $ledgers = Ledger::whereIn('organization_id', $organizations)->orderBy('id', 'desc');
            if ($request->group) {
                $ledgers->whereJsonContains('ledger_group_id', $request->group)
                ->orWhere('ledger_group_id', $request->group);
             }
            if ($request->status) {
                $ledgers->where('status', $request->status == "Active" ? 1 : 0);
            }
            if ($request->date) {
                $dates = explode(' to ', $request->date);
                $start = date('Y-m-d', strtotime($dates[0]));
                $end = date('Y-m-d', strtotime($dates[1]));
                $ledgers->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
            }
            return DataTables::of($ledgers)
                ->addColumn('group_name', function ($ledger) {
                    $groups = $ledger->group();
                    if ($groups && $groups instanceof \Illuminate\Database\Eloquent\Collection) {
                        $groupNames = $groups->pluck('name')->implode(', ');
                    } else if ($groups) {
                        $groupNames = $groups->pluck('name')[0] ?? "-";
                    } else {
                        $groupNames = '';
                    }
                    return $groupNames;
                })->addColumn('costCenter', function ($ledger) {
                    return $ledger->costCenter ? $ledger->costCenter->name : 'N/A';
                })
                ->addColumn('status', function ($ledger) {
                    if ($ledger->status == 1) {
                        $btn = '<span class="badge rounded-pill badge-light-success badgeborder-radius">Active</span>';
                    } else {
                        $btn = '<span class="badge rounded-pill badge-light-danger badgeborder-radius">Inactive</span>';
                    }
                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
                    return $formatedDate;
                })
                ->addColumn('action', function ($ledger) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="' . route('ledgers.edit', ['ledger' => $ledger->id]) . '">
                                <i data-feather="edit-3" class="me-50"></i>
                                <span>Edit</span>
                            </a>
                            
                            <a class="delete-btn dropdown-item"
                                    data-url="' . route('ledgers.destroy', ['ledger' => $ledger->id]) . '"
                                    data-redirect="' . route('ledgers.index') . '"
                                    data-message="Are you sure you want to delete this ledger?">
                                <i data-feather="trash-2" class="me-50"></i> Delete
                            </a>
                        </div>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $groups = Group::where('status', 'active')->where(function ($q) use ($user) {
            $q->where(function ($sub) {
                $sub->whereNotNull('parent_group_id')->whereNull('organization_id');
            })->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->select('id', 'name')->get();
        $ledgers = Ledger::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->orderBy('id', 'desc')->get();
        $mappings = UserOrganizationMapping::where('user_id', $user->id)
            ->with(['organization' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();
           
        return view('ledgers.view_ledgers', compact('groups', 'ledgers', 'mappings', "organizationId"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $costCenters = CostCenter::where('status', 'active')->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->get();
        $groups = Group::where('status', 'active')->where(function ($q) {
            $q->where(function ($sub) {
                $sub->whereNotNull('parent_group_id')->whereNull('organization_id');
            })->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->select('id', 'name')->get();
        return view('ledgers.add_ledger', compact('costCenters', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate the request data
        $request->validate([
            'code' => 'required|string|max:255|unique:erp_ledgers,code',
            'name' => 'required|string|max:255|unique:erp_ledgers,name',
        ]);
        $request->merge([
            'ledger_group_id' => isset($request->ledger_group_id) ? json_encode($request->ledger_group_id) : null,
        ]);
        // Get the authenticated user
        $user = Helper::getAuthenticatedUser();

        $organization = Organization::where('id', $user->organization_id)->first();

        // Create a new ledger record with organization details
        Ledger::create(array_merge($request->all(), [
            'organization_id' => $organization->id,
            'group_id' => $organization->group_id,
            'company_id' => $organization->company_id,
        ]));




        // Redirect with a success message
        return redirect()->route('ledgers.index')->with('success', 'Ledger created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Helper::getAuthenticatedUser();
        $data = Ledger::find($id);
        $costCenters = CostCenter::where('status', 'active')->where('organization_id', Helper::getAuthenticatedUser()->organization_id);
        if ($data->cost_center_id) {
            $costCenters->orWhere('id', $data->cost_center_id);
        }
        $costCenters = $costCenters->get();
        $groups = Group::where('status', 'active')->where(function ($q) {
            $q->whereNull('organization_id')->orWhere('organization_id', Helper::getAuthenticatedUser()->organization_id);
        })->select('id', 'name')->get();
        return view('ledgers.edit_ledger', compact('groups', 'data', 'costCenters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('erp_ledgers')->ignore($id)],
            'name' => ['required', 'string', 'max:255', Rule::unique('erp_ledgers')->ignore($id)],
        ]);
        $request->merge([
            'ledger_group_id' => isset($request->ledger_group_id) ? json_encode($request->ledger_group_id) : null,
        ]);

        $update = Ledger::find($id);
        $update->name = $request->name;
        $update->code = $request->code;
        $update->cost_center_id = $request->cost_center_id;
        $update->ledger_group_id = $request->ledger_group_id;
        $update->status = $request->status;
        $update->save();

        return redirect()->route('ledgers.index')->with('success', 'Ledger updated successfully');
    }

    public function getLedgerGroups($ledgerId)
    {
        $ledger = Ledger::find($ledgerId);
        if (!$ledger) {
            return response()->json(['error' => 'Ledger not found'], 404);
        }
        $ledgerGroups = $ledger->groups();
        if (!$ledgerGroups) {
            return response()->json(['error' => 'No groups found for this ledger'], 404);
        }
        return response()->json($ledgerGroups);
    }

    public function getLedger(Request $request)
    {
        $searchTerm = $request->input('q', '');
        
        $query = Ledger::where('status', 1)
            ->withDefaultGroupCompanyOrg();
        
        if (!empty($searchTerm)) {
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%$searchTerm%")
                    ->orWhere('code', 'LIKE', "%$searchTerm%");
            });
        }
        $results = $query->limit(10)->get(['id', 'code', 'name']);
        
        return response()->json($results);
    }
  
    /**
     * Remove the specified resource from storage.
     */
    public function destroys(string $id)
    {
        $record = Ledger::findOrFail($id);
        $record->delete();
        return redirect()->route('ledgers.index')->with('success', 'Ledger deleted successfully');
    }
    public function destroy($id)
        {
            try {
                $ledger = Ledger::findOrFail($id);
                $referenceTables = [
                    'erp_banks' => ['ledger_id'],
                    'erp_bank_details' => ['ledger_id'],
                    'erp_cogs_accounts' => ['ledger_id'],
                    'erp_discount_master' => ['discount_ledger_id'],
                    'erp_so_item_delivery' => ['ledger_id'],
                    'erp_customers'=>['ledger_id'],
                    'erp_expense_master' => ['expense_ledger_id'],
                    'erp_finance_fixed_asset_registration' => ['ledger_id'],
                    'erp_item_details' => ['ledger_id'],
                    'erp_gr_accounts' => ['ledger_id'],
                    'erp_item_details_history' => ['ledger_id'],
                    'erp_loan_financial_accounts' => ['pro_ledger_id','dis_ledger_id','int_ledger_id','wri_ledger_id'],
                    'erp_payment_vouchers' => ['ledger_id'],
                    'erp_sales_accounts' => ['ledger_id'],
                    'erp_stock_accounts' => ['ledger_id'],
                    'stock_ledger_item_attributes' => ['stock_ledger_id'],
                    'erp_tax_details' => ['ledger_id'],
                    'erp_vendors' => ['ledger_id'],
                ];
        
                $result = $ledger->deleteWithReferences($referenceTables);
        
                if (!$result['status']) {
                    return response()->json([
                        'status' => false,
                        'message' => $result['message'],
                        'referenced_tables' => $result['referenced_tables'] ?? []
                    ], 400);
                }
        
                return response()->json([
                    'status' => true,
                    'message' => $result['message']
                ], 200);
        
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while deleting the ledger: ' . $e->getMessage()
                ], 500);
            }
        }
}
