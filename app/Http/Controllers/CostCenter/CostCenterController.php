<?php

namespace App\Http\Controllers\CostCenter;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use App\Models\CostGroup;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Organization;
use Auth;

class CostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centers = CostCenter::where('organization_id',Helper::getAuthenticatedUser()->organization_id)->orderBy('id', 'desc')->get();
        return view('costCenter.view', compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = CostGroup::where('organization_id',Helper::getAuthenticatedUser()->organization_id)->where('status','active')->get();
        return view('costCenter.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:erp_cost_centers,name',
        ]);

        // Find the organization based on the user's organization_id
        $organization = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->first();


        // Create a new cost center record with organization details
        CostCenter::create(array_merge($request->all(), [
            'organization_id' => $organization->id,
            'group_id' => $organization->group_id,
            'company_id' => $organization->company_id,
        ]));

        // Redirect with a success message
        return redirect()->route('cost-center.index')->with('success', 'Cost Center created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CostCenter::find($id);
        $groups = CostGroup::where('organization_id',Helper::getAuthenticatedUser()->organization_id)->where('status','active')->orWhere('id',$data->cost_group_id)->get();
        return view('costCenter.edit', compact('groups', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('erp_cost_groups')->ignore($id)],
        ]);

        $update = CostCenter::find($id);
        $update->name = $request->name;
        $update->cost_group_id = $request->cost_group_id;
        $update->status = $request->status;
        $update->save();

        return redirect()->route('cost-center.index')->with('success', 'Cost Center updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = CostCenter::findOrFail($id);
        $record->delete();
        return redirect()->route('cost-center.index')->with('success', 'Cost Center deleted successfully');
    }
}
