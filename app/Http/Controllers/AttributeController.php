<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AttributeRequest;
use App\Models\AttributeGroup;
use App\Models\Attribute;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Helpers\Helper;

use Auth;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first(); 
        $organizationId = $organization?->id ?? null;
        $companyId = $organization?->company_id ?? null;
        if ($request->ajax()) {
            $attributeGroups = AttributeGroup::WithDefaultGroupCompanyOrg()
                ->with('attributes')
                ->orderBy('id', 'ASC')
                ->get();
    
            return DataTables::of($attributeGroups)
                ->addIndexColumn()
                ->addColumn('attributes', function ($row) {
                    return implode(', ', $row->attributes->pluck('value')->toArray());
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge rounded-pill ' . ($row->status == 'active' ? 'badge-light-success' : 'badge-light-danger') . ' badgeborder-radius">
                                ' . ucfirst($row->status) . '
                            </span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('attributes.edit', $row->id);
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
                ->rawColumns(['attributes', 'status', 'action'])
                ->make(true);

        }
    
        return view('procurement.attributes.index');
    }
    
    public function create()
    {
        $status = ConstantHelper::STATUS;
        return view('procurement.attributes.create', [
            'status' => $status,
        ]);
    }

    public function store(AttributeRequest $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validated = $request->validated();
        $attributeGroup = AttributeGroup::create([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? ConstantHelper::ACTIVE,
            'organization_id' => $organization->id,
            'group_id' => $organization->group_id,
            'company_id' => $organization->company_id,
        ]);

        $subattributes = $request->input('subattribute', []);
        foreach ($subattributes as $subattribute) {
            $attributeGroup->attributes()->create([
                'value' => $subattribute['value'],
                'attribute_group_id' => $attributeGroup->id,
            ]);
        }

        return response()->json([
            "data" => $attributeGroup,
            'message' => 'Record created successfully',
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $attributeGroup = AttributeGroup::find($id);
        $status = ConstantHelper::STATUS;
        return view('procurement.attributes.edit', [
            'attributeGroup' => $attributeGroup,
            'status' => $status,
        ]);
    }

    public function update(AttributeRequest $request, $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validatedData = $request->validated();
        $validatedData['organization_id'] = $organization->id;
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;
        try {
            $attributeGroup = AttributeGroup::findOrFail($id);
            $attributeGroup->update([
                'name' => $validatedData['name'],
                'status' => $validatedData['status'] ?? $attributeGroup->status,
                'organization_id' => $validatedData['organization_id'],
                'group_id' => $validatedData['group_id'],
                'company_id' => $validatedData['company_id'],
            ]);
            if ($request->has('subattribute')) {
                $newAttributeIds = [];
                foreach ($validatedData['subattribute'] as $subattribute) {
                    if (!empty($subattribute['value'])) {
                        $attributeId = $subattribute['id'] ?? null;
                        if ($attributeId) {
                            $attribute = Attribute::find($attributeId);
                            if ($attribute) {
                                $attribute->update([
                                    'value' => $subattribute['value'],
                                    'attribute_group_id' => $attributeGroup->id,
                                ]);
                                $newAttributeIds[] = $attribute->id;
                            }
                        } else {
                            $newAttribute = $attributeGroup->attributes()->create([
                                'value' => $subattribute['value'],
                                'attribute_group_id' => $attributeGroup->id,
                            ]);
                            $newAttributeIds[] = $newAttribute->id;
                        }
                    }
                }
                $attributeGroup->attributes()->whereNotIn('id', $newAttributeIds)->delete();
            } else {
                $attributeGroup->attributes()->delete();
            }
            return response()->json([
                'status' => true,
                'message' => 'Record updated successfully',
                'data' => $attributeGroup,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getAttributesByGroup($groupId)
    {
        $attributes = Attribute::where('attribute_group_id', $groupId)->get();
        if ($attributes->isEmpty()) {
            return response()->json(['message' => 'No attributes found'], 404);
        }
        return response()->json($attributes);
    }

    public function deleteAttributeDetail($id)
    {
        try {
            $attributeDetail = Attribute::findOrFail($id);
            $result = $attributeDetail->deleteWithReferences();
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
            $attributeGroup = AttributeGroup::findOrFail($id);
            $referenceTables = [
                'erp_attributes' => ['attribute_group_id'], 
            ];
            $result = $attributeGroup->deleteWithReferences($referenceTables);
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
                'message' => 'An error occurred while deleting the attribute group: ' . $e->getMessage()
            ], 500);
        }
    }

    
}
