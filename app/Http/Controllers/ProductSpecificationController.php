<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Models\ProductSpecification;
use App\Models\ProductSpecificationDetail;
use Illuminate\Http\Request;
use App\Http\Requests\ProductSpecificationRequest;
use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use Auth;
use App\Models\Organization;

class ProductSpecificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first(); 
        $organizationId = $organization?->id ?? null;
        $companyId = $organization?->company_id ?? null;
    
        if ($request->ajax()) {
            $query = ProductSpecification::WithDefaultGroupCompanyOrg(); 
            $productSpecifications = $query->get();
    
            return DataTables::of($productSpecifications)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return '<span class="badge rounded-pill badge-light-' . ($row->status === 'active' ? 'success' : 'danger') . ' badgeborder-radius">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('product-specifications.edit', $row->id);
                    return '<div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="' . $editUrl . '">
                                        <i data-feather="edit-3" class="me-50"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    
        return view('procurement.product-specification.index');
    }
    
    
    public function create()
    {
        $status = ConstantHelper::STATUS;
        return view('procurement.product-specification.create', compact('status'));
    }

    public function store(ProductSpecificationRequest $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validated = $request->validated();
        $validated['organization_id'] = $organization->id;
        $validated['group_id'] = $organization->group_id;
        $validated['company_id'] = $organization->company_id;
    
        $productSpecification = ProductSpecification::create($validated);
    
        if ($request->has('specification_details')) {
            $specificationDetails = $request->input('specification_details');
            foreach ($specificationDetails as $detail) {
                if (!empty($detail['name'])) {
                    $productSpecification->details()->create($detail);
                }
            }
        }
       
        return response()->json([
            'status' => true,
            'message' => 'Record created successfully',
            'data' => $productSpecification,
        ]);
    }

    public function show(ProductSpecification $productSpecification)
    {
        // Implement this method if needed
    }

    public function edit($id)
    {
        $productSpecification = ProductSpecification::findOrFail($id);
        $status = ConstantHelper::STATUS;
        return view('procurement.product-specification.edit', compact('productSpecification', 'status'));
    }

    public function update(ProductSpecificationRequest $request, $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validated = $request->validated();
        $productSpecification = ProductSpecification::findOrFail($id);
        $validated['organization_id'] = $organization->id;
        $validated['group_id'] = $organization->group_id;
        $validated['company_id'] = $organization->company_id;
        $productSpecification->update($validated);

        if ($request->has('specification_details')) {
            $specificationDetails = $request->input('specification_details');
            $newDetailIds = [];
            foreach ($specificationDetails as $detail) {
                $detailId = $detail['id'] ?? null;
                if ($detailId) {
                    $existingDetail = $productSpecification->details()->find($detailId);
                    if ($existingDetail) {
                        $existingDetail->update($detail);
                        $newDetailIds[] = $detailId;
                    }
                } else {
                    $newDetail = $productSpecification->details()->create($detail);
                    $newDetailIds[] = $newDetail->id;
                }
            }
            $productSpecification->details()->whereNotIn('id', $newDetailIds)->delete();
        } else {
            $productSpecification->details()->delete();
        }
        return response()->json([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => $productSpecification,
        ]);
    }
    
    public function deleteSpecificationDetail($id)
    {
        try {
            $specificationDetail = ProductSpecificationDetail::findOrFail($id);
            $result = $specificationDetail->deleteWithReferences();
            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }
            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully.',
            ], 200);
        
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the record: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $productSpecification = ProductSpecification::findOrFail($id);
            $referenceTables = [
                'erp_product_specification_details' => ['product_specification_id'],
            ];
            $result = $productSpecification->deleteWithReferences($referenceTables);
            
            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully',
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the record: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function getSpecificationDetails($id)
    {
        try {
            $specification = ProductSpecification::findOrFail($id);
            $specificationDetails = ProductSpecificationDetail::where('product_specification_id', $id)
                ->get(['id', 'name']);
            return response()->json([
                'specifications' => $specificationDetails,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Specification not found'], 404);
        }
    }
}
