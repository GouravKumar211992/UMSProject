<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest; 
use App\Helpers\ConstantHelper;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use Auth;
use App\Models\Organization;


class UnitController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first(); 
        $organizationId = $organization?->id ?? null;
        $companyId = $organization?->company_id ?? null;
    
        if ($request->ajax()) {
            $query = Unit::WithDefaultGroupCompanyOrg() 
                ->orderBy('id', 'ASC');
            $units = $query->get();
    
            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return '<span class="badge rounded-pill ' . ($row->status == 'active' ? 'badge-light-success' : 'badge-light-danger') . ' badgeborder-radius">
                                ' . ucfirst($row->status) . '
                            </span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('units.edit', $row->id);
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
    
        return view('procurement.unit.index');
    }
    
    public function create()
    {
        $status = ConstantHelper::STATUS;
        return view('procurement.unit.create', compact('status'));
    }

    public function store(UnitRequest $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validated = $request->validated();
        $validated['organization_id'] = $organization->id;
        $validated['group_id'] = $organization->group_id;
        $validated['company_id'] = $organization->company_id;
        $unit = Unit::create($validated);
        return response()->json([
            'status' => true,
            'message' => 'Record created successfully',
            'data' => $unit,
        ]);
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $status = ConstantHelper::STATUS;
        return view('procurement.unit.edit', compact('unit', 'status'));
    }

    public function update(UnitRequest $request, $id)
    {  
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validated = $request->validated();
        $validated['organization_id'] = $organization->id;
        $validated['group_id'] = $organization->group_id;
        $validated['company_id'] = $organization->company_id;
        $unit = Unit::findOrFail($id);
        $unit->update($validated);
        return response()->json([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => $unit,
        ]); 
    }

    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $result = $unit->deleteWithReferences(); 
    
            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the unit: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
