<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Helpers\SaleModuleHelper;
use App\Helpers\ServiceParametersHelper;
use App\Models\Compliance;
use Yajra\DataTables\DataTables;
use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use App\Models\Item;
use App\Models\TaxDetail;
use App\Models\Ledger;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Helpers\TaxHelper;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Helpers\Helper;
use Auth;
use App\Models\Organization;


class TaxController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first(); 
        $organizationId = $organization?->id ?? null;
        $companyId = $organization?->company_id ?? null;
    
        if ($request->ajax()) {
            $taxes = Tax::withDefaultGroupCompanyOrg()
                ->orderBy('id', 'ASC')
                ->get();
    
            return DataTables::of($taxes)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    return '<span class="badge rounded-pill badge-light-' . ($row->status === 'active' ? 'success' : 'danger') . ' badgeborder-radius">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function($row) {
                    return '<div class="dropdown">
                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="' . route('tax.edit', $row->id) . '">
                                <i data-feather="edit-3" class="me-50"></i>
                                <span>Edit</span>
                            </a>
                        </div>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    
        return view('procurement.tax.index');
    }
    

    public function create()
    {
        $applicationTypes = ConstantHelper::TAX_APPLICATION_TYPE;
        $supplyTypes = ConstantHelper::PLACE_OF_SUPPLY_TYPES;
        $statuses = ConstantHelper::STATUS;
        $taxTypes = ConstantHelper::TAX_TYPES;
        $ledgers = Ledger::withDefaultGroupCompanyOrg()->get(); 
        return view('procurement.tax.create', [
            'applicationTypes' => $applicationTypes,
            'supplyTypes' => $supplyTypes,
            'statuses' => $statuses,
            'taxTypes' => $taxTypes,
            'ledgers' => $ledgers,
        ]);
    }
    

    public function store(TaxRequest $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validatedData = $request->validated();
        $validatedData['organization_id'] = $organization->id;
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;
        DB::beginTransaction();
    try {
        $tax = Tax::create([
            'tax_group' => $validatedData['tax_group'],
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
            'applicability_type' => $validatedData['applicability_type'],
            'organization_id' => $validatedData['organization_id'], 
            'group_id' => $validatedData['group_id'],
            'company_id' => $validatedData['company_id'],
        ]);

        foreach ($validatedData['tax_details'] as $detail) {
            TaxDetail::create([
                'tax_id' => $tax->id,
                'ledger_id' => $detail['ledger_id'],
                'ledger_group_id' => $detail['ledger_group_id'],
                'tax_type' => $detail['tax_type'],
                'tax_percentage' => $detail['tax_percentage'],
                'place_of_supply' => $detail['place_of_supply'],
                'is_purchase' => isset($detail['is_sale']) && $detail['is_sale'] == '1',
                'is_sale' => isset($detail['is_purchase']) && $detail['is_purchase'] == '1',
            ]);
        }

        DB::commit();
        return response()->json([
            'status' => true,
            'message' => 'Record created successfully',
            'data' => $tax,
        ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        //
    }

   
    public function edit(string $id)
    {
        $tax = Tax::findOrFail($id);
        $applicationTypes = ConstantHelper::TAX_APPLICATION_TYPE;
        $supplyTypes = ConstantHelper::PLACE_OF_SUPPLY_TYPES;
        $statuses = ConstantHelper::STATUS;
        $taxTypes = ConstantHelper::TAX_TYPES;
        $ledgers = Ledger::withDefaultGroupCompanyOrg()->get();
        $ledgerGroups = Group::where('status',1)->get();
        return view('procurement.tax.edit', [
            'tax' => $tax,
            'applicationTypes' => $applicationTypes,
            'supplyTypes' => $supplyTypes,
            'statuses' => $statuses,
            'taxTypes' => $taxTypes,
            'ledgers' => $ledgers,
            'ledgerGroups' => $ledgerGroups
        ]);
    }
    

    public function update(TaxRequest $request, string $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validatedData = $request->validated();
        $validatedData['organization_id'] = $organization->id;
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;
    
        DB::beginTransaction();
        try {
            $tax = Tax::findOrFail($id);
            $tax->update([
                'tax_group' => $validatedData['tax_group'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
                'applicability_type' => $validatedData['applicability_type'],
                'organization_id' => $validatedData['organization_id'],
                'group_id' => $validatedData['group_id'],
                'company_id' => $validatedData['company_id'],
            ]);
            if ($request->has('tax_details')) {
                $newTaxDetailIds = []; 
                foreach ($validatedData['tax_details'] as $detailData) {
                    if (isset($detailData['id'])) {
                        $taxDetail = $tax->taxDetails()->where('id', $detailData['id'])->first();
                        if ($taxDetail) {
                            $taxDetail->update([
                                'ledger_id' => $detailData['ledger_id'],
                                'ledger_group_id' => $detailData['ledger_group_id'],
                                'tax_type' => $detailData['tax_type'],
                                'tax_percentage' => $detailData['tax_percentage'],
                                'place_of_supply' => $detailData['place_of_supply'],
                                'is_purchase' => isset($detailData['is_purchase']) && $detailData['is_purchase'] == '1',
                                'is_sale' => isset($detailData['is_sale']) && $detailData['is_sale'] == '1',
                            ]);
                            $newTaxDetailIds[] = $taxDetail->id;
                        }
                    } else {
                        $detailData['tax_id'] = $tax->id;  
                        $newTaxDetail = TaxDetail::create($detailData); 
                        $newTaxDetailIds[] = $newTaxDetail->id;
                    }
                }
                $tax->taxDetails()->whereNotIn('id', $newTaxDetailIds)->delete();
            } else {
                $tax->taxDetails()->delete();
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Record updated successfully',
                'data' => $tax,
            ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while updating the record',
                    'error' => $e->getMessage(),
                ], 500);
            }
    }

    public function deleteTaxDetail($id)
    {
        DB::beginTransaction();

        try {
            $taxDetail = TaxDetail::findOrFail($id);
            $result = $taxDetail->deleteWithReferences();
            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }
            DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted successfully.',
        ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the record: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $tax = Tax::findOrFail($id);
    
            $referenceTables = [
                'erp_tax_details' => ['tax_id'],
            ];
    
            $result = $tax->deleteWithReferences($referenceTables);
    
            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }
    
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully',
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the Tax record: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function testCalculateTax(Request $request)
    {
        $user = auth()->user();
        $organization = $user->organization;
        $firstAddress = $organization->addresses->first();
        if ($firstAddress) {
            $fromCountry = $firstAddress->country_id;
            $fromState = $firstAddress->state_id;
        } else {
            return response()->json(['error' => 'No address found for the organization.'], 404);
        }
    
        $price = $request->input('price', 6000);
        $hsnId = $request->input('hsn_id', 1);
        $upToCountry = $request->input('country_id', $fromCountry);
        $upToState = $request->input('state_id', $fromState);
        $transactionType = $request->input('transaction_type', 'sale'); 
    
        try {
            $taxDetails = TaxHelper::calculateTax( $hsnId,$price,$fromCountry,$fromState,$upToCountry,$upToState,$transactionType);
            return response()->json($taxDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function calculateItemTax(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $firstAddress = $organization->addresses->first();
        if ($firstAddress) {
            $companyCountryId = $firstAddress->country_id;
            $companyStateId = $firstAddress->state_id;
        } else {
            return response()->json(['error' => 'No address found for the organization.'], 404);
        }
        $price = $request->input('price', 6000);
        $hsnId = null;
        $item = Item::find($request -> item_id);
        if (isset($item)) {
            $hsnId = $item -> hsn_id;
        } else {
            return response()->json(['error' => 'Invalid Item'], 500);
        }
        $transactionType = $request->input('transaction_type', 'sale');
        if ($transactionType === "sale") {
            $fromCountry = $companyCountryId;
            $fromState = $companyStateId;
            $upToCountry = $request->input('party_country_id', $companyCountryId);
            $upToState = $request->input('party_state_id', $companyStateId);
        } else {
            $fromCountry = $request->input('party_country_id', $companyCountryId);
            $fromState = $request->input('party_state_id', $companyStateId);
            $upToCountry = $companyCountryId;
            $upToState = $companyStateId;
        }
        try {
            $taxDetails = TaxHelper::calculateTax( $hsnId,$price,$fromCountry,$fromState,$upToCountry,$upToState,$transactionType);
            return response()->json($taxDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function calculateTaxForSalesModule(Request $request)
    {
        try {
            $user = Helper::getAuthenticatedUser();
            $organization = $user->organization;
            $firstAddress = $organization->addresses->first();
            if ($firstAddress) {
                $companyCountryId = $firstAddress->country_id;
                $companyStateId = $firstAddress->state_id;
            } else {
                return response()->json(['error' => 'No address found for the organization.'], 404);
            }
            $price = $request->input('price', 0);
            $hsnId = null;
            $item = Item::find($request -> item_id);
            if (isset($item)) {
                $hsnId = $item -> hsn_id;
            } else {
                return response()->json(['error' => 'Invalid Item'], 500);
            }
            $transactionType = $request->input('transaction_type', 'sale');
            if ($transactionType === "sale") {
                $fromCountry = $companyCountryId;
                $fromState = $companyStateId;
                $upToCountry = $request->input('party_country_id', $companyCountryId);
                $upToState = $request->input('party_state_id', $companyStateId);
            } else {
                $fromCountry = $request->input('party_country_id', $companyCountryId);
                $fromState = $request->input('party_state_id', $companyStateId);
                $upToCountry = $companyCountryId;
                $upToState = $companyStateId;
            }
            $taxRequired = SaleModuleHelper::checkTaxApplicability($request -> customer_id ?? 0, $request -> header_book_id ?? 0);
            if ($taxRequired)
            {
                $taxDetails = TaxHelper::calculateTax( $hsnId,$price,$fromCountry,$fromState,$upToCountry,$upToState,$transactionType);
                return response()->json($taxDetails);
            }
            else
            {
                return response()->json([]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    
   
}
