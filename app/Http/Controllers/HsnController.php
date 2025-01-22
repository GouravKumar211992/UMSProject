<?php
namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\HsnRequest;
use App\Models\Hsn;
use App\Models\HsnTaxPattern;
use App\Models\Tax;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Helpers\Helper; 
use Auth;
use App\Models\Organization;


class HsnController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::find($user->organization_id); 
        $organizationId = $organization->id;
        $companyId = $organization->company_id;
    
        if ($request->ajax()) {
            $hsns = Hsn::withDefaultGroupCompanyOrg()
                ->get();
    
            return DataTables::of($hsns)
                ->addIndexColumn()
                ->editColumn('status', function($row) {
                    return '<span class="badge rounded-pill ' . ($row->status == 'active' ? 'badge-light-success' : 'badge-light-danger') . ' badgeborder-radius">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function($row) {
                    return '<div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="' . route('hsn.edit', $row->id) . '">
                                        <i data-feather="edit-3" class="me-50"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);

        }
    
        return view('procurement.hsn.index');
    }
    
    public function create()
    {
        $taxGroups = Tax::where('status', ConstantHelper::ACTIVE)->withDefaultGroupCompanyOrg()->get();
        $status = ConstantHelper::STATUS;
        $hsnCodeType = ConstantHelper::HSN_CODE_TYPE;
        return view('procurement.hsn.create', compact('taxGroups', 'status','hsnCodeType'));
    }
    
    public function store(HsnRequest $request)
    {
        
        $user = Helper::getAuthenticatedUser();
        
        $organization = $user->organization;
        $validatedData = $request->validated();
        $validatedData['organization_id'] = $organization->id;
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;
    
        DB::beginTransaction();

        try {
            $hsn = Hsn::create([
                'type' => $validatedData['type'],
                'code' => $validatedData['code'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
                'organization_id' => $validatedData['organization_id'],
                'group_id' => $validatedData['group_id'],
                'company_id' => $validatedData['company_id'],
            ]);

            $taxPatterns = $validatedData['tax_patterns'] ?? [];
           
            foreach ($taxPatterns as $pattern) {
                $pattern['hsn_id'] = $hsn->id; 
                HsnTaxPattern::create($pattern);
            }
    
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Record created successfully',
                'data' => $hsn,
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
    

    public function show(Hsn $hsn)
    {
        // Optionally show details of an HSN record
    }

    public function edit($id)
    {
        $hsn = Hsn::with('taxPatterns')->findOrFail($id);
        $taxGroups = Tax::where('status', ConstantHelper::ACTIVE)->withDefaultGroupCompanyOrg()->get();
        $status = ConstantHelper::STATUS;
        $hsnCodeType = ConstantHelper::HSN_CODE_TYPE;
        return view('procurement.hsn.edit', compact('hsn', 'taxGroups', 'status','hsnCodeType'));
    }

    public function update(HsnRequest $request, string $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validatedData = $request->validated();
        $validatedData['organization_id'] = $organization->id;
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;

        DB::beginTransaction();

        try {
            $hsn = Hsn::findOrFail($id);
            $hsn->update([
                'type' => $validatedData['type'],
                'code' => $validatedData['code'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
                'organization_id' => $validatedData['organization_id'],
                'group_id' => $validatedData['group_id'],
                'company_id' => $validatedData['company_id'],
            ]);
    
            if ($request->has('tax_patterns')) {
                $newTaxPatternIds = [];
                foreach ($validatedData['tax_patterns'] as $patternData) {
                    if (isset($patternData['id'])) {
                        $hsn->taxPatterns()->where('id', $patternData['id'])->update([
                            'from_price' => $patternData['from_price'],
                            'upto_price' => $patternData['upto_price'],
                            'tax_group_id' => $patternData['tax_group_id'],
                        ]);
                        $newTaxPatternIds[] = $patternData['id'];
                    } else {
                        $patternData['hsn_id'] = $hsn->id;
                        $newTaxPattern = HsnTaxPattern::create($patternData);
                        $newTaxPatternIds[] = $newTaxPattern->id;
                    }
                }
                $hsn->taxPatterns()->whereNotIn('id', $newTaxPatternIds)->delete();
            } else {
                $hsn->taxPatterns()->delete();
            }
    
       DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => $hsn,
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
    
    public function deleteHsnDetail($id)
    {
        DB::beginTransaction();

        try {
            $hsnDetail = HsnTaxPattern::findOrFail($id);
            $result = $hsnDetail->deleteWithReferences();
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
            $hsn = Hsn::findOrFail($id);

            $referenceTables = [
                'erp_hsn_tax_patterns' => ['hsn_id'],
            ];

            $result = $hsn->deleteWithReferences($referenceTables);

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
            'message' => 'Record deleted successfully'
        ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the HSN: ' . $e->getMessage()
            ], 500);
        }
    }

}
